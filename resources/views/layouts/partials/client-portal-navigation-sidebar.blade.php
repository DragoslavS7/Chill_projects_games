<div class="row">
    <div class="col-xs-12 pb-10 pt-17 pl-25 pr-25 fn-c-white">
        <span class="fn-c-white fn-s-20">CLIENT DASHBOARD</span>

        <div class="dropdown-section mt-19">
            <a href="{{ route('client-portal.games.index') }}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-cubes" aria-hidden="true"></i>
                GAMES
            </a>

            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#games"
               aria-expanded="false"
               aria-controls="games"
            ></i>

            <div class="collapse" id="games">
                @can('create', new \App\Game())
                <a href="{{ route('client-portal.games.templates') }}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-file-o" aria-hidden="true"></i>
                     -- NEW GAME
                </a>
                @endcan
                <a href="{{ route('client-portal.leaderboards.index') }}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                    -- LEADERBOARDS
                </a>
            </div>
        </div>


        <div class="dropdown-section mt-19">
            <a href="{{ route('client-portal.quizzes.index') }}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-book" aria-hidden="true"></i>
                CONTENT
            </a>

            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#content"
               aria-expanded="false"
               aria-controls="content"></i>

            <div class="collapse" id="content">
                <a href="{{ route('client-portal.quizzes.index') }}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    QUIZZES
                </a>
                @can('create', new \App\Quiz())
                <a href="{{ route('client-portal.quizzes.create') }}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    -- NEW QUIZ
                </a>
                @endcan

                <a href="{{route('client-portal.questions.index')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                    QUESTIONS
                </a>
                @can('create', new \App\Question())
                <a href="{{route('client-portal.questions.create')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    -- NEW QUESTIONS
                </a>
                @endcan

                <div class="pl-25 mt-19 bl-white">
                    <p class="fn-c-white fn-s-15 menu-top-level-font">
                        PhotoBombed
                    </p>
                    <a href="{{route('client-portal.photo-bombed.quizzes.index')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        QUIZZES
                    </a>
                    @can('create', new \App\Quiz())
                    <a href="{{route('client-portal.photo-bombed.quizzes.create')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                        -- NEW QUIZ
                    </a>
                    @endcan
                    <a href="{{route('client-portal.photo-bombed.questions.index')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                        QUESTIONS
                    </a>
                    @can('create', new \App\Question())
                    <a href="{{route('client-portal.photo-bombed.questions.create')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                        -- NEW QUESTIONS
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="dropdown-section mt-19">
            <a href="{{ route('client-portal.game-analytics.index') }}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-pie-chart" aria-hidden="true"></i>
                GAME ANALYTICS
            </a>
        </div>

        <div class="dropdown-section mt-19">
            <a href="{{ route('client-portal.admin-analytics.index') }}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-pie-chart" aria-hidden="true"></i>
                ADMIN ANALYTICS
            </a>
        </div>


        <div class="dropdown-section mt-19">
            <a href="{{ route('client-portal.team-members.index') }}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-users" aria-hidden="true"></i>
                TEAM MEMBERS
            </a>
            @can('create', new \App\User())
            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#team-members"
               aria-expanded="false"
               aria-controls="team-members"></i>

            <div class="collapse" id="team-members">
                <a href="{{ route('client-portal.team-members.create') }}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    -- NEW MEMBER
                </a>
            </div>
            @endcan
        </div>

        @can('create', new \App\User())
        <div class="dropdown-section mt-19">
            <a href="{{ route('client-portal.settings.index') }}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-cog" aria-hidden="true"></i>
                SETTINGS
            </a>
        </div>
        @endcan

        <div class="dropdown-section mt-19">
            <a href="{{route('client-portal.help.index')}}" class="fn-c-white fn-s-15 menu-top-level-font">
                <i class="fa fa-life-ring" aria-hidden="true"></i>
                HELP
            </a>

            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#help"
               aria-expanded="false"
               aria-controls="help"></i>

            <div class="collapse" id="help">
                <a href="{{route('client-portal.help.index')}}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    -- SUPPORT
                </a>

                <a href="{{ route('client-portal.help.documentation') }}" class="fn-c-gray fn-s-13 d-block mt-5 menu-low-level-font">
                    <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                    -- DOCUMENTATION
                </a>
            </div>
        </div>
    </div>
</div>