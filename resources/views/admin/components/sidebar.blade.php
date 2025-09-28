<aside id="sidebar" class="min-vh-100">
  <ul class="sidebar-nav">
    <li class="sidebar-item">
      <a href="{{ route('admin.permissions.create') }}" class="sidebar-link">
        <i class="lni lni-user-4"></i> <span>Permissions</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="" class="sidebar-link">
        <i class="lni lni-agenda"></i> <span>Task</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
        <i class="lni lni-shield-2-check"></i>
        <span>Auth</span>
      </a>
      <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="sidebar">
        <li class="sidebar-item"><a href="" class="sidebar-link">Login</a></li>
        <li class="sidebar-item"><a href="" class="sidebar-link">Register</a></li>
      </ul>
    </li>

    <li class="sidebar-item">
      <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
        <i class="lni lni-layout-9"></i>
        <span>Multi Level</span>
      </a>
      <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="sidebar">
        <li class="sidebar-item">
          <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
            Two Links
          </a>
          <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
            <li class="sidebar-item"><a href="#" class="sidebar-link">Link</a></li>
            <li class="sidebar-item"><a href="#" class="sidebar-link">Link</a></li>
          </ul>
        </li>
      </ul>
    </li>

    <li class="sidebar-item">
      <a href="#" class="sidebar-link">
        <i class="lni lni-bell-1"></i> <span>Notification</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="#" class="sidebar-link">
        <i class="lni lni-bell-1"></i> <span>Setting</span>
      </a>
    </li>
  </ul>
  <div class="sidebar-footer d-flex justify-content-center">
    <button class="sidebar-link border-0 bg-transparent w-100 text-start">
      <i class="lni lni-exit"></i> <span>Logout</span>
    </button>
  </div>
</aside>
