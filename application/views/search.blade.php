@layout('master')

@section('canvas')
<canvas class="blur" data-src="{{ $photo_background }}"></canvas>
@endsection

@section('content')
<div class="container">
    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
                    <li class="nav-header">Cities</li>
                    @foreach (Post::$cities as $city)
                        @if (Input::has('city') && Input::get('city') == $city)
                        <li class="active"><a href="/search?{{ Post::query_string($city, Input::get('q')) }}">{{ $city }}</a></li>
                        @else
                        <li><a href="/search?{{ Post::query_string($city, Input::get('q')) }}">{{ $city }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <div class="well">
                @if (Input::has('city') && Input::has('q'))
                    <h1>{{ urldecode(Input::get('city')) }}</h1>
                    <p>Showing posts matching '{{ urldecode(Input::get('q')) }}' in {{ urldecode(Input::get('city')) }}</p>
                @elseif (Input::has('city'))
                    <h1>{{ urldecode(Input::get('city')) }}</h1>
                    <p>Showing posts in {{ urldecode(Input::get('city')) }}</p>
                @elseif (Input::has('q'))
                    <h1>{{ urldecode(Input::get('q')) }}</h1>
                    <p>Showing posts matching '{{ urldecode(Input::get('q')) }}'</p>
                @else
                    <h1>Search for what you're looking for...</h1>
                    <p>We've got everything in your local area. Search using your own terms and then filter it by location.</p>
                @endif
            </div>

            <?php $i = 0; ?>
            @foreach ($posts as $post)

            @if ($i % 3 == 0)
            <!--.row-fluid-->
            <div class="row-fluid posts-main">
            @endif

            <div class="span4 well post-preview">
                <a href="/posts/{{ $post->id }}">
                    <img class="thumbnail" src="{{ URL::to_asset('photos/main/' . $post->main_photo_name) }}" style="max-width:200px;max-height:75px" />
                </a>
                <h2><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h2>
                <span class="label label-info">{{ $post->location }}</span><br />
                <p>{{ $post->description }}</p>
                <p><a class="btn" href="/posts/{{ $post->id }}">View details &raquo;</a></p>
            </div><!--/span4-->

            @if ($i % 3 == 2)
            </div>
            <!--/.row-fluid-->
            @endif

            <?php ++$i; ?>

            @endforeach
        </div><!--/span-->
    </div><!--/row-->
</div>
@endsection

@section('footer_js')
<script>
    (function($) {
        /**
         * Light layer on top of a canvas element to represent an image displayed
         * within.  Pass in a canvas element and an Image object and you'll see the
         * image within the canvas element.  Use the provided methods (e.g. blur) to
         * manipulate it.
         *
         * @constructor
         * @param {HTMLElement} element HTML canvas element.
         * @param {Image} image Image object.
         */
        var CanvasImage = function(element, image) {
            this.image = image;
            this.element = element;
            this.element.width = this.image.width;
            this.element.height = this.image.height;
            this.context = this.element.getContext("2d");
            this.context.drawImage(this.image, 0, 0);
        };

        CanvasImage.prototype = {
            /**
             * Runs a blur filter over the image.
             *
             * @param {int} strength Strength of the blur.
             */
            blur: function (strength) {
                this.context.globalAlpha = 0.5; // Higher alpha made it more smooth
                // Add blur layers by strength to x and y
                // 2 made it a bit faster without noticeable quality loss
                for (var y = -strength; y <= strength; y += 2) {
                    for (var x = -strength; x <= strength; x += 2) {
                        // Apply layers
                        this.context.drawImage(this.element, x, y);
                        // Add an extra layer, prevents it from rendering lines
                        // on top of the images (does makes it slower though)
                        if (x>=0 && y>=0) {
                            this.context.drawImage(this.element, -(x-1), -(y-1));
                        }
                    }
                }
                this.context.globalAlpha = 1.0;
            }
        };

        /**
         * Initialise an image on the page and blur it.
         */

        var canvas_el = $('canvas.blur');
        var photo_pool = 6;

        if (canvas_el.length) {
            var url = canvas_el.attr('data-src'),
                image,
                canvasImage;
                console.log(url);

            image = new Image();
            image.onload = function () {

                canvasImage = new CanvasImage(canvas_el[0], this);
                try{console.time('blur_4');}catch(err){}
                canvasImage.blur(4);
                try{console.timeEnd('blur_4');}catch(err){}
            };
            image.src = url;
        }
    })(jQuery);
</script>
@endsection