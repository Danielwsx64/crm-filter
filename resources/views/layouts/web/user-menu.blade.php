<!-- Dropdown com detalhes da conta de usuário -->
<div data-ls-module="dropdown" class="ls-dropdown ls-user-account">
  <a href="#" class="ls-ico-user">
    <!-- <img src="images/avatar&#45;example.jpg" alt="" /> -->
    <span class="ls-name">
      {{ user_full_name(Auth::user()) }}
    </span>
    ({{ Auth::user()->user_name }})
  </a>

  <nav class="ls-dropdown-nav ls-user-menu">
    <ul>
      <li>
          <a href="{{ route('logout') }}"
              onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
              Logout
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
        </li>
      </ul>
  </nav>
</div>
