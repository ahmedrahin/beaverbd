<div class="table-responsive">
@if( $shippings->count() == 0 )
    <div class="alert alert-danger" role="alert">
        No Data Found!
    </div>
@else
    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Sl.</th>
                <th>Shipping Name</th>
                <th>Shipping Charge</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @php
                $counter = 1; // Initialize counter variable
            @endphp
            @foreach ($shippings as $shipping)
                <tr>
                    <td>{{$counter++}}</td>
                    <td>{{$shipping->shipping_name}}</td>
                    <td align="middle">
                        {{$shipping->charge}}.00৳
                    </td>
                    <td align="middle">
                        @php
                            $switchId = 'switch' . $counter;
                        @endphp
                        @if($shipping->status == 0)
                            <input type="checkbox" id="{{ $switchId }}" class="status-toggle" data-shipping-id="{{ $shipping->id }}" switch="success" />
                            <label for="{{ $switchId }}" data-on-label="Active" data-off-label="Inactive"></label>
                        @else
                            <input type="checkbox" id="{{ $switchId }}" class="status-toggle" data-shipping-id="{{ $shipping->id }}" switch="success" checked />
                            <label for="{{ $switchId }}" data-on-label="Active" data-off-label="Inactive"></label>
                        @endif
                    </td>
                    <td class="action">
                        <button class="deleteButton" data-shipping-id="{{ $shipping->id }}">
                            <i class="ri-delete-bin-2-fill"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
</div>