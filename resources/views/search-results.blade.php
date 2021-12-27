@extends('layout')

@section('title', 'Search Results')

@section('extra-css')

@endsection

@section('content')


    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Search Results</span>
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


    <div class="search-container container">
        <h1>Search Results</h1>
        <p class="mt-2 mb-4">{{ $products->total() }} result(s) for '{{ request()->input('query') }}'</p>

        <div class="products text-center">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Details</th>
                  <th scope="col">Description</th>
                  <th scope="col">Price</th>
                  {{-- <th scope="col">Image</th> --}}
                </tr>
              </thead>
              <tbody>
                @forelse ($products as $key => $product)
                    <tr>
                      <th>
                        <a href="{!! route('shop.show',$product->slug) !!}">{{ $product->name }}</a>
                      </th>
                      <td>{!! $product->details !!}</td>
                      <td>{!! \Illuminate\Support\Str::limit($product->description, 80) !!}</td>
                      <td>${{ $product->price/100 }}</td>
                      {{-- <td><img src="{{ productImage($product->image) }}" alt="product"></td> --}}
                    </tr>
                @empty
                  <div style="text-align:left;">No Items found!</div>
                @endforelse
              </tbody>
            </table>
        </div> <!-- end products -->
        <div class="spacer"></div>
        <div class="mb-5">
          {{ $products->appends(request()->input())->links() }}
        </div>
    </div> <!-- end Search Container -->


@endsection


@section('extra-js')
@endsection