<?php
namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\ChatCustomerInfo;
use SplQueue;

class WebSocketController implements MessageComponentInterface
{
  protected $clients;
  private $users;
  private $customers;
  private $supporters;
  private $queue;

  public function __construct()
  {
    $this->clients = new \SplObjectStorage;
    $this->users = [];
    $this->queue = new SplQueue();
    $this->supporters = [];
  }

  private function queueToArray() {
    $entries = [];
    $this->queue->rewind();
    while ($this->queue->valid()) {
      $entries[] = json_decode(json_encode($this->queue->current()), true);
      $this->queue->next();
    }
    return $entries;
  }

  public function onOpen(ConnectionInterface $conn)
  {
    echo "Connection: {$conn->resourceId} has connected\n";
    $this->clients->attach($conn);
    $this->users[$conn->resourceId] = $conn;
  }

  public function onClose(ConnectionInterface $conn)
  {
    $this->clients->detach($conn);
    echo "Connection: {$conn->resourceId} has disconnected\n";
    unset($this->users[$conn->resourceId]);

    if (in_array($conn->resourceId, $this->supporters)) {
      foreach ($this->supporters as $key => $supporter) {
        if ($supporter == $conn->resourceId) {
          unset($this->supporters[$key]);
        }
      }
    }

    if (in_array($conn->resourceId, $this->customers)) {
      foreach ($this->customers as $key => $customer) {
        if ($customer == $conn->resourceId) {
          unset($this->customers[$key]);
        }
      }
    }

    $this->queue->rewind();
    $count = 0;
    while ($this->queue->valid()) {
      if ($this->queue->current()['resourceId'] === $conn->resourceId) {
        $this->queue->offsetUnset($count++);
        break;
      }
    }
  }

  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
  }

  public function onMessage(ConnectionInterface $from, $msg)
  {
    $data = json_decode($msg);
    if (isset($data->command)) {
      switch ($data->command) {
        case "INIT:CUSTOMER":
          if (isset($data->userId)) {
            $this->customers[$data->userId] = $from->resourceId;
          }
          $from->send(json_encode($data));
          break;
        case "INIT:SUPPORTER":
          if (isset($data->userId)) {
            $this->supporters[$data->userId] = $from->resourceId;
          }
          $from->send(json_encode($data));
          break;
        case "SUBMIT":
          $submit = $data->submit;
          $customer = ChatCustomerInfo::create([
            'customer_id' => $data->userId,
            'department'  => $submit->department,
            'name'        => $submit->customerName,
            'email'       => $submit->customerEmail,
            'phone'       => $submit->customerPhone,
            'question'    => $submit->customerQuestion,
          ]);

          $count_of_queue = $this->queue->count();

          $this->queue->push([
            'userId'      => $data->userId,
            'submit'      => $data->submit,
            'resourceId'  => $from->resourceId,
          ]);

          $from->send(json_encode([
            'command' => 'WAITING',
            'order'   => $count_of_queue,
          ]));

          $customers = $this->queueToArray();

          foreach ($this->supporters as $key => $supporter) {
            if (isset($this->users[$supporter])) {
              $this->users[$supporter]->send(json_encode([
                'command' => 'CUSTOMER_REQUEST',
                'request' => $customers,
              ]));
            }
          }
          break;
        case "ACCEPT":

          break;
        case "message":
          if (isset($this->userresources[$data->to])) {
            foreach ($this->userresources[$data->to] as $key => $resourceId) {
              if (isset($this->users[$resourceId])) {
                $this->users[$resourceId]->send($msg);
              }
            }
            $from->send(json_encode($this->userresources[$data->to]));
          }

          if (isset($this->userresources[$data->from])) {
            foreach ($this->userresources[$data->from] as $key => $resourceId) {
              if (isset($this->users[$resourceId]) && $from->resourceId != $resourceId) {
                $this->users[$resourceId]->send($msg);
              }
            }
          }
          break;
        default: break;
      }
    }
  }
}
