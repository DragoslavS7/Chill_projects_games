@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow">
        <div class="pt-30 pb-30">
            <div class="row fn-s-19">
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
                            {{ Form::label('is_active', $gameTemplate->is_active ? 'Yes' : 'No') }}
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
                            {{ Form::label('name', $gameTemplate->name) }}
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
                            {{ Form::label('source', $gameTemplate->source) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('description', 'Description') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::label('description', $gameTemplate->description) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('genre', 'Genre') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::label('genre', $gameTemplate->genre) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('video_url', 'Video URL') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::label('video_url', $gameTemplate->video_url) }}
                        </div>
                    </div>

                    <div class="row mb-8">
                        <div class="col-xs-3 text-right w-22p">
                            {{ Form::label('demo_url', 'Demo URL') }}
                        </div>
                        <div class="col-xs-9">
                            {{ Form::label('demo_url', $gameTemplate->demo_url) }}
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
                                    <div class="col-xs-3 text-right w-20p">
                                        {{ Form::label('assign_clients', 'Assigned Clients') }}
                                    </div>

                                    <div class="col-xs-offset-6 col-xs-1 text-right fn-s-19 pt-5">
                                        <b>{{ Form::label('search', 'Search') }}</b>
                                    </div>
                                    <div class="col-xs-2 pr-5 pull-right">
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
                        </p>
                        <div class="col-xs-12 mt-10">
                            <img id='template_icon-preview' class="img-responsive" src="{{ $gameTemplate->template_icon }}" alt="">
                        </div>
                        @if($templateIconError = $errors->first('template_icon'))
                            <div class="row">
                                <div class="col-xs-12 fn-s-13">
                                    <p class="text-center text-danger"><i>{{$templateIconError}}</i></p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row mb-8 mt-40">
                        <p class="text-center">
                            Screenshot
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
                    </div>
                </div>
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
                    return '<button title="View client" class="view btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">'+
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</button>';
                },

            }
        ];


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
                addSelectColumn: false,
                customSearchInputSelector: '#search'
            });

            // Submit form on click on update or save button
            $('#update, #save').click(function(){
                document.querySelector('#new-game-template').submit();
            });

            // On click on view button go to view page
            $('table').on('click', '.view', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.client-portals.view', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            $(window).resize(function(){
                $('#templates-table').css('width','100%');
            });
        });

    </script>
@endpush