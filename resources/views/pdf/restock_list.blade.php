<h1>Restock Priority List</h1>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>Item</th>
            <th>Size</th>
            <th>Current Stock</th>
            <th>Threshold</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->size_label }}</td>
            <td>{{ $item->stock_quantity }}</td>
            <td>{{ $item->low_stock_threshold }}</td>
        </tr>
        @endforeach
    </tbody>
</table>