<div class="top-bar d-flex justify-content-between">
  <div class="d-flex">
    <button id="toggle-btn" type="button">
      <i class="lni lni-dashboard-square-1"></i>
    </button>
    <div class="sidebar-logo">
      <a href="{{ url('/') }}">SOP BAZAAR</a>
    </div>
  </div>

  <div class="d-flex justify-content-end align-items-center">
    <div class="dropdown">
      <button class="btn p-0 border-0 bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img
          src="{{ asset('assets/img/image.png') }}"
          alt="User avatar"
          class="rounded-circle"
          width="40" height="40"
          style="object-fit:cover;"
        />
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow">
        <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
        <li>
          <form method="POST" action="{{ route('logout') }}" >
            @csrf
            <button type="submit" class="dropdown-item">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>
