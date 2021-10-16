@extends('layouts.app', ['activePage' => 'recipes', 'titlePage' => __('Recipe Details')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Recipe Details</h4>
                        <p class="card-category"> Here you can manage recipes</p>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                {{ session('status')}}</span>
                        </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%">
                                <tbody>
                                    <tr>
                                        <td>Recipe ID</td>
                                        <td>#{{ $recipe->id }}</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Phone No</td>
                                        <td>{{ $recipe->phoneNo }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mosque</td>
                                        <td>{{ $order->mosque }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Status</td>
                                        <td style="font-size: 18px;"><span class="badge badge-success">{{ $order->orderStatusName }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Payment Status</td>
                                        <td>{{ $order->paymentStatusName }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created at</td>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated at</td>
                                        <td>{{ $order->updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Notes</td>
                                        <td></td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class='table table-shopping'>
                                <thead class=" text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>1</td>
                                    <td>Sohat Water Pack 6x1L</td>
                                    <td><img width="80" src="https://clickomart.imgix.net/products/1600978977263_gallery_sohat-water-pack-6-x-1-l.jpg?format=jpeg&auto=enhance&sharp=30&trim=auto&trim-sd=2" alt=""></td>
                                    <td>3</td>
                                    <td>12 KD</td>
                                    <td class="td-actions text-center">
                                        <a href=""><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                <i class="material-icons">close</i>
                                            </button></a>
                                    </td>
                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-shopping">
                                                <thead>
                                                    <td><b>Total</b></td>
                                                    <td>12 KD</td>
                                                </thead>
                                            </table>
                                        </div>
                                    </td>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <a href="/orders" class="btn btn-sm btn-primary">Back to list</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection