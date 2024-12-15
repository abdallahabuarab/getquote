<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="" class="waves-effect">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                @if(session('role') !== 'provider')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Users management</span>
                    </a>

                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('userspa.index')}}">Users</a></li>
                            </ul>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('providerspa.index')}}">Providers</a></li>
                            </ul>

                </li>
                @endif
                <li>
                    <a href="{{route('zipcodes.index')}}" class="waves-effect">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <span>ZipCodes</span>
                    </a>
                <li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-list-alt"></i>
                        <span>Categories</span>
                    </a>

                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('class.index')}}">Classes</a></li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{route('services.index')}}">Services</a></li>
                            </ul>


                </li>
                @if(session('role') !== 'provider')
                <li>
                    <a href="{{route('rejects.index')}}" class=" waves-effect">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <span>Rejects</span>
                    </a>

                </li>

                <li>
                    <a href="{{route('requests.index')}}" class="waves-effect">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        <span>Customers Requests</span>
                    </a>



                <li>
                <li>
                    {{-- <a href="{{route('orders.index')}}" class="waves-effect">
                        <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                        <span>Customers Orders</span>
                    </a> --}}



                <li>


                <li>


@endif

                <li>
                    <a href="{{route('admin.logout')}}" class="waves-effect">
                        <i class="ri-shut-down-line"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
        $('#sidebar-menu').metisMenu();
        $('#sidebar-menu a.has-arrow').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $(this).next('ul.sub-menu').find('ul.sub-menu').slideToggle('fast');
        });


        // Add smooth scrolling to all links
        $('a').on('click', function(event) {
            if (this.hash !== "") {
                event.preventDefault();
                var hash = this.hash;
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function(){
                    window.location.hash = hash;
                });
            }
        });
    });
</script>



{{-- <div class="vertical-menu">

    <div data-simplebar class="h-10">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-user-shield"></i>
                        <span>Administration</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('roles') }}">Roles</a></li>
                        <li><a href="{{ route('permissions') }}">Permissions</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <span>Website Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('home.slide') }}">Slide</a></li>
                    </ul>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('about.page') }}">About</a></li>
                    </ul>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('footer.home') }}">Footer</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Users management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('users') }}">Users</a></li>
                    </ul>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('merchants.index') }}">Merchants</a></li>
                    </ul>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('branches.index') }}">Merchant Branches</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('contact.message') }}" class="waves-effect">
                        <i class="fa fa-comments" aria-hidden="true"></i>
                        <span>Contact Messages</span>
                    </a>
                <li>
                <li>
                    <a href="{{ route('categories.index') }}" class="waves-effect">
                        <i class="fas fa-list-alt"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('subscriptions.index') }}" class="waves-effect">
                        <i class="fas fa-credit-card"></i>
                        <span>Subscriptions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('offers.index') }}" class="waves-effect">
                        <i class="fas fa-percent"></i>
                        <span>Offers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('corporates') }}" class="waves-effect">
                        <i class="fas fa-building"></i>
                        <span>Corporates</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('countries.index') }}" class="waves-effect">
                        <i class="fas fa-globe-americas"></i>
                        <span>Countries</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pos.index') }}" class="waves-effect">
                        <i class="fas fa-cash-register"></i>
                        <span>Points Of Sale</span>
                    </a>
                </li>



                <li>
                    <a href="index.html" class="waves-effect">
                        <i class="ri-shut-down-line"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

<!-- Add this script at the end of your HTML file -->
<!-- Add this script at the end of your HTML file -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sidebar-menu').metisMenu();
        $('#sidebar-menu a.has-arrow').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $(this).next('ul.sub-menu').find('ul.sub-menu').slideToggle('fast');
        });
    });
</script> --}}
