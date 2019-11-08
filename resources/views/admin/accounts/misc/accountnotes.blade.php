<div class="card mb-1">
  <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color">
    <a class="text-light float-left" data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion5" aria-expanded="true" aria-controls="exampleAccordion5">
      <strong>
        <i class="fa fa-edit"></i> Account Notes <i class="fa fa-caret-down"></i>
      </strong>
    </a>
  </div>
  <div class="card-body pt-0 pb-0">
    <div id="exampleAccordion" data-children=".item">
      <div class="item">
        <div class="collapse {{ intval(auth()->user()->role) !== 4 ? 'show' : '' }} table-responsive" id="exampleAccordion5" role="tabpanel">
          <table class="table table-sm table-responsive-sm table-bordered mb-0">
            @if (isset($accountnotes))
              @foreach ($accountnotes as $note)
              <tr>
                <td>
                  <i class="fa fa-user"></i>
                  <small><strong>{{ $note->username }}</strong> {{ $note->created_at }}</small><br>
                  <div style="font-weight:700">{!! $note->notes !!}</div>
                </td>
              </tr>
              @endforeach
            @endif
            <tr>
              <td class="word-break" colspan="2" width="100%">
                <trix-pro-editor name="accountnotes" placeholder="Enter Account Notes"/>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
