<div class="row">
    <div class="col-xs-12 pb-10 pt-17 pl-25 pr-25 fn-c-white">
        <span class="fn-c-white fn-s-20">ADMIN DASHBOARD</span>

        <div class="dropdown-section mt-19">
            <a href="{{ route('admin-portal.client-portals.index') }}" class="fn-c-white fn-s-15">
                <i class="fa fa-users" aria-hidden="true"></i>
                CLIENTS
            </a>

            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#clients"
               aria-expanded="false"
               aria-controls="clients"
            ></i>

            <div class="collapse" id="clients">
                <a href="{{ route('admin-portal.client-portals.create') }}" class="fn-c-gray fn-s-13 d-block mt-5">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                     -- NEW CLIENT
                </a>
            </div>
        </div>


        <div class="dropdown-section mt-19">
            <a href="{{ route('admin-portal.game-templates.index') }}" class="fn-c-white fn-s-15">
                <i class="fa fa-cubes" aria-hidden="true"></i>
                GAME TEMPLATES
            </a>

            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#game-template"
               aria-expanded="false"
               aria-controls="game-template"></i>

            <div class="collapse" id="game-template">
                <a href="{{ route('admin-portal.game-templates.create') }}" class="fn-c-gray fn-s-13 d-block mt-5">
                    <i class="fa fa-file-o" aria-hidden="true"></i>
                    -- NEW TEMPLATE
                </a>
            </div>
        </div>

        <div class="dropdown-section mt-19">
            <a href="{{ route('admin-portal.team-members.index') }}" class="fn-c-white fn-s-15">
                <i class="fa fa-users" aria-hidden="true"></i>
                TEAM MEMBERS
            </a>

            <i class="fa fa-chevron-down fn-s-14 pull-right dropdown-button"
               aria-hidden="true"
               role="button"
               data-toggle="collapse"
               href="#team-members"
               aria-expanded="false"
               aria-controls="team-members"></i>

            <div class="collapse" id="team-members">
                <a class="fn-c-gray fn-s-13 d-block mt-5" href="{{ route('admin-portal.team-members.create') }}">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    -- NEW MEMBER
                </a>
            </div>
        </div>

        <div class="dropdown-section mt-19">
            <a href="{{ route('admin-portal.game-analytics.index') }}" class="fn-c-white fn-s-15">
                <i class="fa fa-pie-chart" aria-hidden="true"></i>
                GAME ANALYTICS
            </a>
        </div>

    </div>
</div>