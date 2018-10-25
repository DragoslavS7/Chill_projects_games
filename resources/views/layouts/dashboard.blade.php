@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row no-gutters d-flex">

            <div class="col-xs-2 bg-ebony-clay fn-fm-orbitron zi-1 menu-background h-100-vh-min">
                @if(isUberAdminPortal())
                    @include('layouts.partials.admin-portal-navigation-sidebar')
                @else
                    @include('layouts.partials.client-portal-navigation-sidebar')
                @endif
            </div>


            <div class="col-xs-10">
                <div class="row bg-white pt-13 pb-4 pl-10 fn-s-29 shadow wraper-background">
                    <div class="col-xs-8">
                        @if(isUberAdminPortal())
                            <p><strong>ADMIN LOGO</strong></p>
                        @else
                            @if(request()->clientPortal->logo)
                                <a href="{{route('client-portal.home')}}" class="pb-10 d-inline-block" >
                                    <img src="{{request()->clientPortal->logo}}" class="h-40">
                                </a>
                            @else
                                <p><strong>CLIENT LOGO</strong></p>
                            @endif
                        @endif
                    </div>
                    <div class="col-xs-offset-1 col-xs-3 pt-15">

                        <p class="text-uppercase fn-s-16  text-right">
                            <span class="text-limit">{{$user->email}}</span>
                            <a href="{{route('user.auth.logout')}}" class="ml-10">
                                <i class="fa fa-sign-out fn-s-20"></i>
                            </a>
                        </p>

                    </div>
                </div>

                @hasSection('dashboard-title')
                    <div class="row fn-fm-orbitron pt-20 pl-10">
                        <div class="col-xs-6 fn-s-29">
                            @hasSection('photo-bombed-image')
                                <div class="col-xs-2">
                                    <img src="@yield("photo-bombed-image", "Photo bomb image")" alt="" width="80" height="80" class="d-inline-block" />
                                </div>
                            @endif
                            <div class="col-xs-10">
                                <p>

                                    @yield("dashboard-title", "Dashboard Title")
                                    @hasSection('photo-bombed-image')
                                        <br>
                                        <span class="fn-s-15 ml-45">PhotoBombed</span>
                                    @endif
                                </p>
                            </div>




                        </div>
                        <div class="col-xs-6">
                            @yield("dashboard-actions")
                        </div>
                    </div>
                @endif

                <div class="row mt-10">
                    @yield("dashboard-middle-content")
                </div>

                <div class="row p-20">

                        @yield("dashboard-main-content")
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function () {
            // Set dropdowns icons initial state
            $('.dropdown-button').each(function () {
                var isExpanded = $(this).attr('aria-expanded') === 'true';

                if (isExpanded) {
                    $(this).removeClass('fa-chevron-down')
                        .addClass('fa-chevron-up');
                }
            });

            // Handle dropdowns icons on click
            $('.dropdown-button').click(function () {
                $(this).toggleClass('fa-chevron-up fa-chevron-down');
            });

            // Set documentation dropdowns icons initial state
            $('.documentation-dropdown-button').each(function () {
                var isExpanded = $(this).attr('aria-expanded') === 'true';

                if (isExpanded) {
                    $(this).removeClass('fa-plus')
                        .addClass('fa-minus');
                }
            });

            // Handle documentation dropdowns icons on click
            $('.documentation-dropdown-button').click(function () {
                $(this).toggleClass('fa-minus fa-plus');
            });
        });
    </script>
@endpush