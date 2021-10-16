<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      {{-- <a class="navbar-brand" href="#">{{ $titlePage }}</a> --}}
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      <!-- <form class="navbar-form">
        <div class="input-group no-border">
        <input type="text" value="" class="form-control" placeholder="Search...">
        <button type="submit" class="btn btn-white btn-round btn-just-icon">
          <i class="material-icons">search</i>
          <div class="ripple-container"></div>
        </button>
        </div>
      </form> -->
      <ul class="navbar-nav">
        <!-- <li class="nav-item">
          <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="material-icons">dashboard</i>
            <p class="d-lg-none d-md-block">
              {{ __('Stats') }}
            </p>
          </a>
        </li> -->

        <?php

        use Illuminate\Support\Facades\DB;

        $notifications = DB::table('panel_notifications')->where('seen', 0)->get();
        ?>

        <li class="nav-item dropdown">
          <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">notifications</i>
            @if(count($notifications) != 0)
            <span class="notification">{{ count($notifications) }}</span>
            @endif
            <p class="d-lg-none d-md-block">
              {{ __('Some Actions') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(count($notifications) != 0)
            @foreach($notifications as $notification)
            <input type="hidden" class="not_id" name="not_id" value="{{$notification->id}}" />
            <a class="dropdown-item" onclick='getMessage()' href="{{ $notification->url }}">{{ $notification->message .' '. Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</a>
            @endforeach
            @else
            <p class="dropdown-item" >No Notifications Found</p>
            @endif
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block">
              {{ __('Account') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
            <!-- <a class="dropdown-item" href="#">{{ __('Settings') }}</a> -->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  function getMessage() {
    var notId = parseInt($('.not_id').val());
    console.log(notId);
    $.ajax({
      url: "/notification/update",
      method: "GET",
      type: "GET",
      data: {
        not_id: notId,
      },
      success: function(data) {
        console.log(data);
      }
    });
  }
</script>