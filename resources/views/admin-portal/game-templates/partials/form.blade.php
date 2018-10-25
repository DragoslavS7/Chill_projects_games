@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow">
        <div class="pt-30 pb-30">
            <div class="row">
                {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl,
                               'id' => 'new-game-template', 'enctype' => 'multipart/form-data' ]) }}
                <div class="col-xs-8">
                    @if($saveError = $errors->first('save'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('is_active', 'Is template active') }}
                        </div>
                        <div class="col-xs-9">
                            <label class="switch mt-5">
                                {{ Form::checkbox('is_active', $gameTemplate->is_active, [ 'id' => 'is_active']) }}
                                <span class="slider round"></span>
                            </label>

                        </div>
                    </div>

                    <div class="row mb-8">
                        @if($nameError = $errors->first('name'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$nameError}}</i></p>
                                </div>
                            </div>
                        @endif

                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('name', 'Template Name *') }}
                        </div>
                        <div class="col-xs-9 {{ $nameError ? 'has-error': '' }}">
                            {{ Form::text('name', $gameTemplate->name, [ 'class' => 'form-control' , 'id' => 'template_name']) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        @if($sourceError = $errors->first('source'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$sourceError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('source', 'Select Source') }}
                        </div>

                        <div class="col-xs-9 {{ $sourceError ? 'has-error': '' }}">
                            {{ Form::select('source', $gamesSources, $gameTemplate->source, [ 'class' => 'form-control' , 'id' => 'source']) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('description', 'Description') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::text('description', $gameTemplate->description, [ 'class' => 'form-control' , 'id' => 'description']) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('genre', 'Genre') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::text('genre', $gameTemplate->genre, [ 'class' => 'form-control' , 'id' => 'genre']) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('video_url', 'Video URL') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::text('video_url', $gameTemplate->video_url, [ 'class' => 'form-control' , 'id' => 'video_url']) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('demo_url', 'Demo URL') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::text('demo_url', $gameTemplate->demo_url, [ 'class' => 'form-control' , 'id' => 'demo_url']) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('is_default', 'Is this game available for all clients?') }}
                        </div>
                        <div class="col-xs-9">
                            <label class="">
                            {{ Form::checkbox('is_default','1',$gameTemplate->is_default,['class' => 'd-none' , 'id' => 'is_default'])}}
                                <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mb-8">
                        @if($assignClientsError = $errors->first('assign_client_portals'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$assignClientsError}}</i></p>
                                </div>
                            </div>
                        @endif

                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('assign_clients', 'Assigned Clients') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::text('assign_client_portals', $assignedClientPortals, [ 'class' => 'form-control d-none' , 'id' => 'assign_clients']) }}

                            @if(!$areThereAnyClientPortal)
                                <p>
                                    <b>
                                        No Clients Assigned.
                                        <u>
                                            <a href="{{ route('admin-portal.game-templates.index') }}" class="fn-c-mine-shaft">
                                                Assign client?
                                            </a>
                                        </u>
                                    </b>
                                </p>
                            @endif
                        </div>
                    </div>
                        @if($areThereAnyClientPortal)
                            <div class="row fn-s-19 p-20">
                                <div class="col-xs-12 p-0">
                                    <div class="col-xs-12">
                                        <div class="col-xs-2 p-0">
                                            {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                                               'assign_all' => 'Assign All',
                                                                               'unassign_all' => 'Unassign All',
                                            ], null, ['class' => 'form-control','id'=>'bulk_actions']) }}
                                        </div>

                                        <div class="col-xs-1">
                                            <button type="button" class="btn btn-light" id="apply">Apply</button>
                                        </div>

                                        <div class="col-xs-offset-6 col-xs-1 text-right fn-s-19 pt-5">
                                            <b>{{ Form::label('search', 'Search') }}</b>
                                        </div>
                                        <div class="col-xs-2 pr-5">
                                            <input type="text" id="search" class="form-control">
                                        </div>

                                    </div>
                                    <div class="col-xs-12">
                                        <table class="table table-bordered" id="templates-table">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
                <div class="col-xs-4">
                    <div class="row mb-8">
                        <p class="text-center">
                            Template Icon
                            <br />
                            <small class="fn-s-13">(128x128)</small>
                        </p>
                        <div class="col-xs-12 mt-10">
                            <img id='template_icon-preview' class="img-responsive m-0-auto" src="{{ $gameTemplate->template_icon }}" alt="">
                        </div>
                        @if($templateIconError = $errors->first('template_icon'))
                            <div class="row">
                                <div class="col-xs-12 fn-s-13">
                                    <p class="text-center text-danger"><i>{{$templateIconError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 mt-10 {{ $templateIconError ? 'has-error' : '' }}">
                        <span class="btn btn-light btn-file m-0-auto d-table">
                            Upload Image  {{ Form::file('template_icon', [ 'class' => 'form-control' , 'id' => 'template_icon']) }}
                        </span>
                        </div>
                    </div>

                    <div class="row mb-8 mt-40">
                        <p class="text-center">
                            Screenshot
                            <br />
                            <small class="fn-s-13">(800x600)</small>
                        </p>
                        <div class="col-xs-12 mt-10">
                            <img id='screenshot-preview' class="img-responsive" src="{{ $gameTemplate->screenshot }}" alt="">
                        </div>
                        @if($screenshotError = $errors->first('screenshot'))
                            <div class="row">
                                <div class="col-xs-12 fn-s-13">
                                    <p class="text-center text-danger"><i>{{$screenshotError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 mt-10 {{ $screenshotError ? 'has-error' : '' }}">
                        <span class="btn btn-light btn-file m-0-auto d-table">
                            Upload Image  {{ Form::file('screenshot', [ 'class' => 'form-control' , 'id' => 'screenshot']) }}
                        </span>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var DATA_TABLE_COLUMNS = [
            {
                title: 'Clients',
                data: 'company_name',
                name: 'company_name',
                defaultContent: ''
            },
            {
                title: '<p class="text-center">View</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    var url = '{{ route('admin-portal.client-portals.view', '_ID_') }}';
                    url = url.replace('_ID_', row.id);


                    return '<a title="View client" class="view btn p-0 bg-transparent m-0-auto d-block fn-c-mine-shaft" href="' + url + '">'+
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</a>';
                },

            },
            {
                title: '<p class="text-center">Assign</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    var isAssigned = false;
                    var value = document.querySelector('#assign_clients').value || '[]';

                    var assignedTempaltesIds = JSON.parse(value);

                    if (assignedTempaltesIds && assignedTempaltesIds.indexOf(row.id) > -1) {
                        isAssigned = true;
                    }

                    return '<div class="m-0-auto d-table">' +
                        '<label class="switch mt-5">' +
                        '<input data-id="' + row.id + '" type="checkbox" ' + (isAssigned ? 'checked' : '' ) + '>' +
                        '<span class="slider round"></span>' +
                        '</label>' +
                        '</div>';
                }
            }
        ];

        // On click on checkbox show notify
        var confirmModal = new ConfirmDialog({
            modal_title:'Is this game available for all clients?',
            modal_dialog_message:'Do you want to add this template to all client portals after saving your changes?'
        });

        function cancelShow () {
            var prevState = !$(this).is(':checked');
            $(this).prop('checked', prevState);

        }

         $('#is_default').on('click', confirmModal.show(null,cancelShow));

        // Show preview when file is selected
        $(document).on('change', ':file', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                var previewImageSelector = "#" + this.name + "-preview";

                reader.onload = function (e) {
                    document.querySelector(previewImageSelector).src = e.target.result;
                };

                reader.readAsDataURL(this.files[0]);
            }
        });

        $(document).ready(function () {
            var dataTable = new DataTablesMyArcadeChef('#templates-table', {
                ajax: '{{ route('admin-portal.client-portals.data-tables') }}',
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });

            // Submit form on click on update or save button
            $('#update, #save').click(function(){
                document.querySelector('#new-game-template').submit();
            });

            // Handle assign
            $('#templates-table').on('change', '.switch :checkbox', function(){
                var isAssigned = $(this).is(":checked");
                var id = parseInt(this.dataset.id);

                var $assignedTemplates = $('#assign_clients');
                var value = $assignedTemplates.val() || '[]';
                value = JSON.parse(value);

                if(isAssigned){
                    if(value.indexOf(id) == -1){
                        value.push(id);
                    }
                }else{
                    var index = value.indexOf(id);
                    if(index > -1){
                        value.splice(index, 1);
                    }
                }

                $assignedTemplates.val(JSON.stringify(value));
            })

            // On click on apply button preform the bulk action
            $('#apply').on('click', function() {

                var $rows = $('.select-row');
                var $rowsAssigned = [];

                var $assignedClientPortals = $('#assign_clients');
                var value = $assignedClientPortals.val() || '[]';
                value = JSON.parse(value);

                $rows.each(function(){
                    if(this.checked){
                        $rowsAssigned.push($(this).parents('tr').find('.switch :checkbox'));
                    }
                });

                var selected = $('#bulk_actions :selected').text();

                switch (selected){
                    case 'Assign All':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));

                            if(value.indexOf(id) == -1){
                                value.push(id);
                                $row.prop('checked',true);
                            }
                        });
                        $assignedClientPortals.val(JSON.stringify(value));
                        break;
                    case 'Unassign All':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));

                            var index = value.indexOf(id);
                            if(index > -1){
                                value.splice(index, 1);
                                $row.prop('checked',false);
                            }
                        });
                        $assignedClientPortals.val(JSON.stringify(value));
                        break;
                }
            });

            $(window).resize(function(){
                dataTable.dataTable.columns.adjust();
            });
        });

    </script>
@endpush