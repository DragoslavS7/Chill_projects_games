@extends('layouts.dashboard')
@section('page-title', 'Create New Game')
@section('dashboard-title', 'Create New Game')


@section('dashboard-actions')
    @if(count($gameTemplates)!=0)
        <a id="next" href="{{ route('client-portal.games.create', $gameTemplates[0]->id ) }}">
            <button title="Next" class="btn btn-default pull-right">Next</button>
        </a>
    @endif
@endsection


@section('dashboard-main-content')
    <div class="col-xs-5 bg-white shadow wraper-background">
        <div class="row pt-30 pb-30 pr-25 pl-25">
            <div class="row">
                <div class="col-xs-6 ml-30 fn-s-19">
                    <label>
                        <b>Select Game Template</b>
                    </label>
                </div>

                <div class="col-xs-4">
                    {{ Form::select('select_category[]', array_merge(['all' => 'Filter By Category'], $gameTemplatesCategories->toArray() ), null, [ 'class' => 'form-control' ]) }}
                </div>
            </div>

            @if(count($gameTemplates)==0)
                <div class="row">
                    <div class="col-xs-12 mt-25 fn-s-15 fn-c-gray">
                        <label>
                            <b>*You do not have assigned game templates. Please contact your uber admin.</b>
                        </label>
                    </div>
                </div>
            @endif

            @if($templateError = $errors->first('template_error'))
                <div class="row">
                    <div class="col-xs-12 mt-25 fn-s-15 fn-c-gray">
                        <label>
                            <b>{{$templateError}}</b>
                        </label>
                    </div>
                </div>
            @endif

            <div class="row mt-5" id="game-templates">
                @foreach($gameTemplates as $index => $gameTemplate )
                   <div data-id="{{ $gameTemplate->id }}" class="col-md-3 text-center mt-25 game-template w-150-min h-200 w-150 {{$index == 0 ? 'bg-gallery' : ''}}">
                       <img
                               class="d-block m-0-auto w-120-min h-120-min pt-10"
                               src="{{ $gameTemplate->template_icon ? $gameTemplate->template_icon : '/images/app_icon.png'}}"
                               alt="" height="100" width="100"
                               data-screenshot="{{$gameTemplate->screenshot}}"
                               data-video="{{ $gameTemplate->video_url }}"
                               data-demo="{{ $gameTemplate->demo_url }}"
                       >
                       <p class="mt-10">{{ $gameTemplate->name }}</p>
                   </div>
                @endforeach
            </div>
            <div id="loading" class="d-none pt-30">
                <i class="fa fa-refresh fa-spin fa-3x fa-fw d-block m-0-auto "></i>
            </div>

        </div>
    </div>

    <div class="col-xs-offset-1 col-xs-5 bg-white shadow">
        <div class="row pt-30 pb-30">
            <div class="row ml-30 fn-s-19">
                <label>
                    <b id="game-template-name">{{ $gameTemplates[0]->name }}</b>
                </label>
            </div>

            <div class="row ml-30 mt-10">
                <a href="{{ $gameTemplates[0]->video_url }}" id="game-template-video-url" class="fn-c-mine-shaft" target="_blank">
                    <b>View Video</b>
                </a>
                <b id="separator">|</b>
                <a href="{{ $gameTemplates[0]->demo_url }}" id="game-template-demo-url" class="fn-c-mine-shaft" target="_blank">
                    <b>View Game URL</b>
                </a>
            </div>

            <div class="row pl-25 pr-25">
                <div class="col-xs-12 mt-25">
                    <img id='game_screenshot-preview' class="img-responsive d-block m-0-auto" src="{{$gameTemplates[0]->screenshot ? $gameTemplates[0]->screenshot : '/images/app_icon.png'}}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        function selectGameTemplate(imgSrc,templateName,templateVideoUrl,templateDemoUrl){
            $('#game_screenshot-preview').attr('src',imgSrc);
            $('#game-template-name').text(templateName);
            $('#game-template-video-url').attr('href',templateVideoUrl);
            $('#game-template-demo-url').attr('href',templateDemoUrl);
            setLinkVisibility(templateVideoUrl,templateDemoUrl);
        }

        function setLinkVisibility(templateVideoUrl,templateDemoUrl){
            if(templateVideoUrl != ""){
                $('#game-template-video-url').show();
            }else{
                $('#game-template-video-url').hide();
            }
            if(templateDemoUrl != ""){
                $('#game-template-demo-url').show();
                $('#separator').show();
            }else{
                $('#game-template-demo-url').hide();
                $('#separator').hide();
            }
        }

        function setGameCreateUrl(id){
            var url = '{{ route('client-portal.games.create', '_ID_' ) }}';
            url = url.replace('_ID_', id);

            document.querySelector("#next").href = url;

        }

        $(document).ready(function(){
            var $gamesTemplatesWrapper = $('#game-templates');

            $gamesTemplatesWrapper.on('click', '.game-template', function(){

                $('.game-template').removeClass('bg-gallery');

                $(this).addClass('bg-gallery');

                var imageSrc = $(this).find('img').attr('data-screenshot');

                if(!imageSrc){
                    imageSrc = '/images/app_icon.png';
                }

                var templateName = $(this).find('p').text();

                var templateVideoUrl = $(this).find('img').attr('data-video');
                var templateDemoUrl = $(this).find('img').attr('data-demo');
                selectGameTemplate(imageSrc,templateName,templateVideoUrl,templateDemoUrl);

                setGameCreateUrl(this.dataset.id);
            });

            $("select[name='select_category[]']").change(function(){
                var genre = this.value;

                var $loading = $('#loading');

                var $blueprintGameTemplate = $gamesTemplatesWrapper.find(">div:eq(0)").clone().removeClass('bg-gallery');

                $gamesTemplatesWrapper.empty();

                $loading.show();

                $.ajax({
                   url: '{{ route('client-portal.games.templates.filter') }}',
                   type: 'GET',
                   data: {
                       '_token': '{{ csrf_token() }}',
                       genre: genre
                   },
                   success: function(data){
                       $loading.hide();

                       for(var i =0, len =data.length; i < len; i++){
                           var gameTemplateData = data[i];

                           var $gameTemplate = $blueprintGameTemplate.clone();

                           function imageDefault(image) {
                               image.src = "/images/app_icon.png";
                               return image.src;
                           }

                           $gameTemplate.attr('data-id', gameTemplateData.id);
                           $gameTemplate.find('img').attr('src', gameTemplateData.template_icon || imageDefault(this) );
                           $gameTemplate.find('img').attr('data-screenshot', gameTemplateData.screenshot);
                           $gameTemplate.find('img').attr('data-video', gameTemplateData.video_url);
                           $gameTemplate.find('img').attr('data-demo', gameTemplateData.demo_url);
                           $gameTemplate.find('p').text(gameTemplateData.name);

                           $gamesTemplatesWrapper.append($gameTemplate);

                           if(i == 0){
                               $gameTemplate.click();
                           }
                       }
                   },
                   error: function(){
                        //todo show modal
                   }
                });

            });

        });
    </script>
@endpush