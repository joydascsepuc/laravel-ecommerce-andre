@extends('layout')

@section('title', $product->name)

@section('extra-css')

@endsection

@section('content')


    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Shop</span>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>{{ $product->name }}</span>
    @endcomponent

    <div class="container">
        @if (session()->has('success_message'))
          <div class="spacer"></div>
          <div class="alert alert-success">
              {{ session()->get('success_message') }}
          </div>
        @endif

        @if(count($errors) > 0)
          <div class="spacer"></div>
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{!! $error !!}</li>
                  @endforeach
              </ul>
          </div>
        @endif
    </div>

    <div class="product-section container">
        <div>
            <div class="product-section-image">
                <img src="{{ productImage($product->image) }}" alt="product" class="active" id="currentImage">
            </div>
            <div class="product-section-images">
                <div class="product-section-thumbnail selected">
                    <img src="{{ productImage($product->image) }}" alt="product">
                </div>

                @if ($product->images)
                    @foreach (json_decode($product->images, true) as $image)
                    <div class="product-section-thumbnail">
                        <img src="{{ productImage($image) }}" alt="product">
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="product-section-information">
            <h1 class="product-section-title">{{ $product->name; }}</h1>
            <div class="product-section-subtitle">{{ $product->details }}</div>
            <div class="product-section-price">$ {{ ($product->price) / 100 }}</div>

            <br>
            <p>
                {!! $product->description !!}
            </p>
            <br>

            {{-- <a href="#" class="button">Add to Cart</a> --}}

            <form action="{!! route('cart.store') !!}" method="post">
              @csrf
              <input type="hidden" value="{{ $product->id }}" name="id">
              <input type="hidden" value="{{ $product->name }}" name="name">
              <input type="hidden" value="{{ $product->price / 100 }}" name="price">
              <button class="button button-plain" type="submit">Add to Cart</button>
            </form>

        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')

@endsection


@section('extra-js')
    <script>
        const currentImage = document.querySelector('#currentImage');
        const images = document.querySelectorAll('.product-section-thumbnail');

        images.forEach((element) => element.addEventListener('click', thumbnailClick));

        function thumbnailClick(e) {
            currentImage.classList.remove('active');

            currentImage.addEventListener('transitionend', () => {
                currentImage.src = this.querySelector('img').src;
                currentImage.classList.add('active');
            })

            images.forEach((element) => element.classList.remove('selected'));
            this.classList.add('selected');
        }
    </script>
@endsection