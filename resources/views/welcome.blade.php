@extends('layouts.frontend.app')

@section('title')
    Area Rent - Homepage
@endsection

@section('content')
    <div class="header" style="height: 65vh; width: 100vw; overflow: hidden;">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('images/slider 1.jpg') }}" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" style="height: 100%;" src="{{ asset('images/slider 2.jpg') }}"
                        alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{ asset('images/slider 3.jpg') }}" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div id="search">
        <div class="container-fluid">
            <div class="row justify-content-center py-4">
                <h2 class="text-center"><strong>Search commercial space of your choice</strong></h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <form action="{{ route('search') }}" method="GET">
                        @csrf
                        <div class="row justify-content-center">
                            @if (session('search'))
                                <div class="alert alert-danger mt-3" id="alert" roles="alert">
                                    {{ session('search') }}
                                </div>
                            @endif
                        </div>
                        <div class="row" style="justify-content: center">
                            <div class="form-group col-md-3">
                                <input type="text" name="address" placeholder="search an area" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                {{-- <input type="text" name="room" placeholder="room" class="form-control"> --}}
                                <select name="room" class="form-control">
                                    <option value="">size</option>
                                    <option value="800">800sqft</option>
                                    <option value="1100">1100sqft</option>
                                    <option value="120">1200sqft</option>
                                    <option value="1600">1600sqft</option>
                                    <option value="2100">2100sqft</option>
                                    <option value="2200">2200sqft</option>
                                    <option value="4000">4000sqft</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                {{-- <input type="text" name="month" placeholder="month" class="form-control"> --}}
                                <select name="month" class="form-control">
                                    <option value="">Month</option>
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                            </div>
                            <div class="row justify-content-center col-md-12 search-bottom">
                                <div class="form-group col-md-3 col-12">
                                    <input type="text" name="rent" placeholder="rent" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-success">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="content">
        <div class="container">
            <div class="row justify-content-center py-5">
                <h1><strong>Available Spaces</strong></h1>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-9">

                    <div class="row">

                        @forelse ($houses as $house)
                            <div class="col-md-6">
                                <div class="card m-3 house-card">
                                    <div class="card-header">
                                        <img src="{{ asset('storage/featured_house/' . $house->featured_image) }}"
                                            width="100%" class="img-fluid" alt="Card image">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                        <h4><strong><i class="fas fa-map-marker-alt"> {{ $house->location->name }},
                                                    Chattogram</i> </strong></h4>
                                        </p>

                                        <p class="grey"><a class="address"
                                                href="{{ route('house.details', $house->id) }}"><i
                                                    class="fas fa-warehouse">
                                                    {{ $house->address }}</i></a> </p>

                                        <p class="grey"><a class="houseCode"
                                                href="{{ route('house.details', $house->id) }}"><i
                                                    class="fas fa-warehouse">
                                                    {{ $house->plot_code }}</i></a> </p>
                                        <hr>
                                        <p class="grey">{{ $house->area_size }} sqft

                                        </p>
                                        <p class="grey">
                                        <h4>৳ {{ $house->rent }} BDT</i></h4>
                                        <!-- <a href="#" data-houseid="{{ $house->id }}" class="update_wishlist">
                                                                                                                                                                                                                                                                                            <i class="far fa-heart float-right "></i>
                                                                                                                                                                                                                                                                                        </a> -->
                                        <!-- <a href="#"  class="addToWishlist location_id" value="{{ $house->id }}">
                                                                                                                                                                                                                                                                                            <i class="far fa-heart float-right "></i>
                                                                                                                                                                                                                                                                                        </a> -->
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex">
                                            <div>
                                                <a href="{{ route('house.details', $house->id) }}"
                                                    class="btn btn-info">Details</a>
                                            </div>
                                            <div style="margin-left: auto; font-size: 25px;">
                                                <form action="{{ url('renter/wishlist/' . $house->id) }}" method="post">
                                                    @csrf
                                                    <button style="border:none;" type="submit">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h2 class="m-auto py-2 text-white bg-dark p-3">No Commercial Space availabe right now</h2>
                        @endforelse
                    </div>

                    <div id="notifDiv">

                    </div>

                    <div class="panel-heading my-4" style="display:flex; justify-content:center;align-items:center;">
                        <a href="{{ route('house.all') }}" class="btn btn-dark">See All Houses</a>
                    </div>


                </div>
                <div class="col-md-3">
                    <ul class="list-group sort">
                        <li class="list-group-item bg-dark text-light sidebar-heading"><strong>Search By Range</strong>
                        </li>
                        <form action="{{ route('searchByRange') }}" method="get" class="mt-2">
                            <div class="form-group">
                                <input type="number" class="form-control" required name="digit1"
                                    placeholder="enter range (lower value)">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" required name="digit2"
                                    placeholder="enter range (upper value)">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-success btn-block">Search</button>
                            </div>
                        </form>
                    </ul>




                    <ul class="list-group sort">
                        <li class="list-group-item bg-dark text-light sidebar-heading"><strong>Sort By Price</strong></li>
                        <li class="list-group-item order"><a href="{{ route('highToLow') }}">High to low</a></li>
                        <li class="list-group-item order"><a href="{{ route('lowToHigh') }}">Low to High</a></li>
                        <li class="list-group-item order"><a href="{{ route('welcome') }}">Normal Order</a></li>
                    </ul>



                    <ul class="list-group area-show">
                        <li class="list-group-item bg-dark text-light sidebar-heading"><strong>Areas</strong></li>
                        @forelse ($locations as $area)
                            <li class="list-group-item all-areas">
                                <a href="{{ route('available.area.house', $area->id) }}"
                                    class="area-name">{{ $area->name }}
                                    <strong>({{ $area->houses->count() }})</strong></a>
                            </li>
                        @empty
                            <li class="list-group-item">Area not found</li>
                        @endforelse

                    </ul>
                </div>
            </div>

        </div>
    </div>



    <div class="section-4 bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <img src="{{ asset('frontend/img/why.jpg') }}" class="section-4-img img-fluid" width="500px;"
                        height="500px;">
                </div>
                <div class="col-md-5">
                    <h1 class="text-white">Why Choose Us?</h1>

                    <p class="para-1">Lorem ipsum dolor sit amet, consectetur adipisicing elitim id est laborum.dolore
                        magna alsint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laboro. </p>
                    <a href="#" style="text-decoration: none">Join Us</a>
                </div>
            </div>
        </div>
    </div>



    <section id="our-story">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="story">Our Story</h1>
                    <p class="pera">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>

                    <p class="pera">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua Ut enim.</p>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('frontend/img/about-us.png') }}" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css"> -->
    <script>
        var botmanWidget = {
            aboutText: 'ssdsd',
            introMessage: "✋ Hi! I'm form HomeRental"
        };
    </script>

    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection

@section('scripts')
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script> -->
    <script>
        var user_id = "{{ Auth::id() }}";


        $(document).ready(function() {
            $('.update_wishlist').click(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var location_id = $(this).data('houseid');

                $.ajax({
                    type: 'POST',
                    url: '/update_wishlist',
                    data: {
                        location_id: location_id,
                        user_id: user_id,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.action == 'add') {
                            ('#notifDiv').fadeIn();
                            $('#notifDiv').css('background', 'green');
                            $('#notifDiv').text(response.message);
                            setTimeout(() => {
                                $('#notifDiv').fadeOut();
                            }, 3000);
                        } else if (response.action == 'remove') {
                            ('#notifDiv').fadeIn();
                            $('#notifDiv').css('background', 'red');
                            $('#notifDiv').text(response.message);
                            setTimeout(() => {
                                $('#notifDiv').fadeOut();
                            }, 3000);
                        }
                    }
                });
            });
        });
    </script>

    <!-- <script>
        $('.addToWishlist').click(function(e) {
            e.preventDefault();
            var location_id = $(this).find('.location_id').val();
            // var location_id = $(this).val();

            // alert(location_id);
            $.ajax({
                method: "POST",
                url: "/add-to-wishlist",
                data: {
                    'location_id': location_id,
                },
                success: function(response) {
                    $val(response.status);
                }
            });
        });
    </script> -->


    <!-- for payment -->
    <script>
        (function(window, document) {
            var loader = function() {
                var script = document.createElement("script"),
                    tag = document.getElementsByTagName("script")[0];
                script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(
                    7);
                tag.parentNode.insertBefore(script, tag);
            };

            window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload",
                loader);
        })(window, document);
    </script>
    <!-- for payment -->
@endsection
