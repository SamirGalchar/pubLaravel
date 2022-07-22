<div class="col-12 col-lg-3">
    <div class="opt_listing">
        <ul class="list-unstyled ">
            <li class="list-item">
                <a href="{{ route('user.profile') }}" class="@if(request()->routeIs('user.profile')) active @endif text-decoration-none color-08284d font-20">Profile</a>
            </li>
            @if(Auth::user()->isPaid == 'Yes')
            @else
                <li class="list-item">
                    <a href="{{ route('user.free-trial') }}" class="@if(request()->routeIs('user.free-trial')) active @endif text-decoration-none color-08284d font-20">Free Trial</a>
                </li>
                <li class="list-item">
                    <a href="{{ route('user.plans') }}" class="@if(request()->routeIs('user.plans') || request()->routeIs('user.purchase')) active @endif text-decoration-none color-08284d font-20">Workout Videos</a>
                </li>
            @endif   
            @if(Auth::user()->isPaid == 'Yes')
                <li class="list-item">
                    <a href="{{ route('user.videos') }}" class="@if(request()->routeIs('user.videos')) active @endif text-decoration-none color-08284d font-20">Workout Videos</a>
                </li>
            @endif   
            <li class="list-item">
                <a href="{{ route('user.change-password') }}" class="@if(request()->routeIs('user.change-password')) active @endif text-decoration-none color-08284d font-20">Change Password</a>
            </li>
            <li class="list-item">
                <a href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-decoration-none color-08284d font-20">Logout</a>
            </li>
        </ul>
    </div>
</div>