@foreach ($fileData as $data)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td class="text-center">{{ $data['item_name'] ?? '-' }}</td>
        <td class="text-center">{{ $data['part_no'] ?? '-' }}</td>
        <td class="text-center">{{ $data['stock_group'] ?? '-' }}</td>
        <td class="text-center">{{ $data['stock_category'] ?? '-' }}</td>

        @if (!empty($data['Godowns']))
            @foreach ($data['Godowns'] as $godown)
                <td class="text-center">{{ $godown['opening'] ?? '-' }}</td>
                <td class="text-center">{{ $godown['inward'] ?? '-' }}</td>
                <td class="text-center">{{ $godown['outward'] ?? '-' }}</td>
                <td class="text-center">{{ $godown['closing'] ?? '-' }}</td>
            @endforeach
        @endif
    </tr>
@endforeach
