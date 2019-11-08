<!-- Breadcrumb -->
<ol class="breadcrumb pb-0 pt-0 mb-0 bg-gray-100 noPrint">
  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <button type="submit" class="btn btn-sm btn-success mr-2" id="{{ isset($saveId) ? $saveId : '' }}">
      <i class="icon-plus"> Save</i>
    </button>
    <a class="btn btn-sm btn-info mr-2 print-window" href="#">
      <i class="icon-printer"> Print</i>
    </a>
  </div>
  <!-- <li class="mr-0" style="margin:auto">
    <ping/>
  </li> -->
  <!-- <li class="mr-2">
    <a href="/accounts/docusign/create" class="btn btn-sm badge-success">
      <i class="fa fa-file-text"></i> Docusign
    </a>
  </li>
  <li class="mr-2">
    <a href="/accounts/rmaform/create" class="btn btn-sm badge-success">
      <i class="fa fa-file-text"></i> RMA Form
    </a>
  </li> -->
  <breadcrumbs-menu user-name="{{ auth()->user()->username }}" cus-email="{{ isset($customermetadata) ? json_encode($customermetadata) : null }}" />
</ol>
<ol class="breadcrumb pb-0 pt-0 mb-0 bg-gray-100 hide hide-on-screen">
  <table class="table table-bordered">
    <thead>
    </thead>
    <tbody>
      <tr>
        <td class="pt-1 pb-1 pl-1 pr-1">Account Type</td>
        <td class="pt-1 pb-1 pl-1 pr-1">{{ isset($customermetadata) ? $customermetadata->level : '' }}</td>
        <td class="pt-1 pb-1 pl-1 pr-1">Account Name</td>
        <td class="pt-1 pb-1 pl-1 pr-1">{{ isset($account) ? $account->accountname : '' }}</td>
        <td class="pt-1 pb-1 pl-1 pr-1">Date Purchased</td>
        <td class="pt-1 pb-1 pl-1 pr-1">{{ isset($account) ? $account->datecustomerpurchased : '' }}</td>
      </tr>
      <tr>
        <td class="pt-1 pb-1 pl-1 pr-1">Type of order</td>
        <td class="pt-1 pb-1 pl-1 pr-1">{{ isset($account) ? $account->ordertype : '' }}</td>
        <td class="pt-1 pb-1 pl-1 pr-1">Barcode</td>
        <td class="pt-1 pb-1 pl-1 pr-1">@php if(isset($barcode)) echo $barcode @endphp</td>
        <td class="pt-1 pb-1 pl-1 pr-1">Date Printed</td>
        <td class="pt-1 pb-1 pl-1 pr-1">{{ date('Y-m-d') }}</td>
      </tr>
    </tbody>
  </table>
</ol>