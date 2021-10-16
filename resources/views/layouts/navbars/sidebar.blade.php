<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="/" class="simple-text logo-normal">
      {{ __('Admin Panel') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
          <i class="material-icons">dashboard</i>
          <p>{{ __('Dashboard') }}</p>
        </a>
      </li>

      <li class="nav-item{{ $activePage == 'users' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('users.index') }}">
          <i class="material-icons">supervisor_account</i>
          <p>{{ __('Users') }}</p>
        </a>
      </li>

      <li class="nav-item{{ $activePage == 'categories/all' ? ' active' : '' }}">
        <a class="nav-link" href="/categories/all">
          <i class="material-icons">list</i>
          <p>{{ __('Categories') }}</p>
        </a>
      </li>

      <li class="nav-item{{ $activePage == 'cuisines/all' ? ' active' : '' }}">
        <a class="nav-link" href="/cuisines/all">
          <i class="material-icons">public</i>
          <p>{{ __('Cuisine') }}</p>
        </a>
      </li>

      <li class="nav-item{{ $activePage == 'difficulties/all' ? ' active' : '' }}">
        <a class="nav-link" href="/difficulties/all">
          <i class="material-icons">bolt</i>
          <p>{{ __('Difficulties') }}</p>
        </a>
      </li>

      <li class="nav-item {{ ($_SERVER['REQUEST_URI'] == '/recipes/all' || $_SERVER['REQUEST_URI'] == '/recipes/pending' || $_SERVER['REQUEST_URI'] == '/recipes/approved' || $_SERVER['REQUEST_URI'] == '/recipes/disapproved' || $_SERVER['REQUEST_URI'] == '/statuses') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="false">
          <i class="material-icons">content_paste</i>
          <p>{{ __('Recipes') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($_SERVER['REQUEST_URI'] == '/recipes/all' || $_SERVER['REQUEST_URI'] == '/recipes/pending' || $_SERVER['REQUEST_URI'] == '/recipes/approved' || $_SERVER['REQUEST_URI'] == '/recipes/disapproved' || $_SERVER['REQUEST_URI'] == '/statuses') ? ' show' : 'hide' }}" id="laravelExample">
          <ul class="nav">
            <li class="nav-item{{ $_SERVER['REQUEST_URI'] == '/recipes/all' ? ' active' : '' }}">
              <a class="nav-link" href="/recipes/all">
                <i class="material-icons">content_paste</i>
                <span class="sidebar-normal">{{ __('All Recipes') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $_SERVER['REQUEST_URI'] == '/recipes/pending' ? ' active' : '' }}">
              <a class="nav-link" href="/recipes/pending">
                <i class="material-icons">history</i>
                <span class="sidebar-normal">{{ __('Pending Recipes') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $_SERVER['REQUEST_URI'] == '/recipes/approved' ? ' active' : '' }}">
              <a class="nav-link" href="/recipes/approved">
                <i class="material-icons">check_circle_outline</i>
                <span class="sidebar-normal">{{ __('Approved Recipes') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $_SERVER['REQUEST_URI'] == '/recipes/disapproved' ? ' active' : '' }}">
              <a class="nav-link" href="/recipes/disapproved">
                <i class="material-icons">highlight_off</i>
                <span class="sidebar-normal"> {{ __('Disapproved Recipes') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $_SERVER['REQUEST_URI'] == '/statuses' ? ' active' : '' }}">
              <a class="nav-link" href="/statuses">
                <i class="material-icons">dns</i>
                <span class="sidebar-normal"> {{ __('Recipe Statuses') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications.index') }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'settings' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('settings.index') }}">
          <i class="material-icons">settings</i>
          <p>{{ __('Settings') }}</p>
        </a>
      </li>

    </ul>
  </div>
</div>