<table>
  <caption>Checked Inventory Information</caption>
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Team</th>
      <th scope="col">H#</th>
      <th scope="col">PCMHardwareType</th>
      <th scope="col">Stock</th>
      <th scope="col">Returned</th>
      <th scope="col">XYZ Position</th>
      <th scope="col">Stock#</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($checked as $key => $item)
      <tr>
        <th scope="row">{{ $key + 1 }}</td>
        <td>{{ $item['team'] }}</td>
        <td>{{ $item['hnumber'] }}</td>
        <td>{{ $item['pcmhardwaretype'] }}</td>
        <td>
          @if($item['stock'])
            In Stock
          @endif
        </td>
        <td>
          @if($item['returned'])
            Returned
          @endif
        </td>
        <td>{{ $item['xyzposition'] }}</td>
        <td>{{ $item['stocknumber'] }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<style>
  table {
    border: 1px solid;
    border-collapse: collapse;
    width: 90%;
    margin: 0 auto;
    text-align: left;
  }
  tr th {
    background: #eee;
    border: 1px solid;
    font-size: 13px;
  }
  tr td {
    border: 1px solid;
    font-size: 12px;
  }
  caption {
    text-align: left;
    font-size: 18px;
    font-weight: bolder;
    margin-bottom: 10px;
  }
</style>
