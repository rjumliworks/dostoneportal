<tr style="text-align: center;">
    <td>{{ $index }}</td>
    <td>
        <img src="{{ $item->participant->detail->avatar_base64 ?? '' }}" style="width:35px; height:35px; object-fit:cover; border-radius:50%;" alt="Avatar">
    </td>
    <td style="text-align: left;">{{ $item->participant->name ?? '' }}</td>
    <td style="text-align: center;">
        @php
            $affiliation = $item->participant->detail->affiliation ?? null;
            $affiliationName = ($affiliation && $affiliation->name === 'Others')
                ? ($item->participant->detail->others ?? '')
                : ($affiliation->name ?? '');
        @endphp
        {{ $affiliationName }}<br>
        <small style="color: gray;">{{ $item->participant->detail->designation ?? '' }}</small>
    </td>
    <td style="text-align: center;">
        {{ $item->participant->email ?? '' }}<br>
        <small style="color: gray;">{{ $item->participant->mobile ?? '' }}</small>
    </td>
    <td>{{ $item->created_at ?? '-' }}</td>
    <td>{{ $item->status->name ?? '-' }}</td>
</tr>
