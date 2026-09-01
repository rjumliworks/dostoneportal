<!doctype html>
<html lang="en">
    <head>  
        <style>
            @page {
                size: A4 portrait;  /* Ensure portrait */
                margin: 15px;
            }
            body { margin: 15px; }
            table, td, th {
                border: 1.3px solid black;
                margin: 0px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }    
            th {
                padding: 8px;
            }
            td {
                padding: 3px 5px 2px;
                text-align: center;
            }
            .b {
                font-weight: bold;
            }
            td.calendar-noBorder {
                border: none;
                background-color: red;
            }
          
        </style>
    </head>
    <body>
        <?php 
            $employee = json_encode($user); 
            $employee = json_decode($user, true);  
        ?>
        @if($type == 15)
        <div style="float: left; width:46%; margin: 0;">
            <div style="font-family:Arial, Helvetica, sans-serif;">
                <p style="font-size: 9px; margin-left: 10px;"><i>CIVIL SERVICE FORM No.48</i></p>
                <h3 style="font-size: 12px; text-align: center;"><u>DAILY TIME RECORD</u></h3>
                <p style="font-size: 11px; text-align: center; margin-top: -7px;">{{ strtoupper($employee['name']) }}</p>
                <div style="border-bottom: 1px solid #333; width: 94%; margin-left: 10px; margin-top: -8px;"></div>
                <div style="font-size: 10px; width: 50%; float: left;">
                    <p style="margin-top: 2px; margin-left: 10px;">For the month of</p>
                    <p style="margin-left: 10px; margin-top: -5px; ">Official hours for arrival and departure</p>
                </div>
                <div style="font-size: 10px; width: 50%; float: right;">
                    <p style="margin-top: 2px; margin-left: 10px;">{{$month}}-{{$year}}</p>
                    <p style="margin-left: 10px; margin-top: -7px; ">Regular days: 8am-5pm</p>
                    <p style="margin-left: 10px; margin-top: -7px; ">Saturdays: not applicable</p>
                </div>
            </div>
            <table style="font-size: 9px; margin-top: 45px; padding: 10px; font-family:Arial, Helvetica, sans-serif">
                <thead>
                    <tr>
                        <td>Days</td>
                        <td colspan="2">A.M</td>
                        <td colspan="2">P.M</td>
                        <td colspan="2">UNDERTIME</td>
                    </tr>
                    <tr>
                        <td style="width: 8%;"></td>
                        <td style="width: 15%;">Arrival</td>
                        <td style="width: 15%;">Departure</td>
                        <td style="width: 15%;">Arrival</td>
                        <td style="width: 15%;">Departure</td>
                        <td style="width: 15%;">Hours</td>
                        <td style="width: 15%;">Minutes</td>
                    </tr>
                </thead>
                <tbody>
                @php
                    $travelGroups = [];
                    $groupCount = 0;
                    $currentTravelGroup = null;
                      foreach ($lists as $index => $list) {
                        if (isset($list['data']) && is_string($list['data']) && str_contains($list['data'], 'OFFICIAL TRAVEL')) {
                            if ($groupCount > 0 && ($list['travel_group'] ?? null) !== $currentTravelGroup) {
                                $travelGroups[] = [
                                    'start' => $index - $groupCount,
                                    'count' => $groupCount
                                ];
                                $groupCount = 0;
                            }
                            $currentTravelGroup = $list['travel_group'] ?? null;
                            $groupCount++;
                        } else {
                            if ($groupCount > 0) {
                                $travelGroups[] = [
                                    'start' => $index - $groupCount,
                                    'count' => $groupCount
                                ];
                                $groupCount = 0;
                            }
                            $currentTravelGroup = null;
                        }
                    }
                    if ($groupCount > 0) {
                        $travelGroups[] = [
                            'start' => count($lists) - $groupCount,
                            'count' => $groupCount
                        ];
                    }
                @endphp
                @foreach($lists as $loopIndex => $list)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>

                        @if(!$list['is_with'])
                            @php
                                $isGroupStart = collect($travelGroups)->firstWhere('start', $loopIndex);
                                $isInGroup = collect($travelGroups)->contains(function($g) use($loopIndex) {
                                    return $loopIndex >= $g['start'] && $loopIndex < ($g['start'] + $g['count']);
                                });
                            @endphp

                            @if($isGroupStart)
                                @php
                                    $count = $isGroupStart['count'];
                                    $bracketHeight = min(18 * $count, 150);
                                    $bracketRowStep = $bracketHeight / $count;
                                    $bracketTop = $bracketRowStep / 2;
                                    $bracketBarHeight = $bracketHeight - $bracketTop - 8;

                                    if ($count <= 2) {
                                        $travelFontSize = 7;
                                        $maxChars = 60 * $count;
                                    } elseif ($count == 3) {
                                        $travelFontSize = 8;
                                        $maxChars = 100 * $count;
                                    } elseif ($count == 4) {
                                        $travelFontSize = 9;
                                        $maxChars = 130 * $count;
                                    } else {
                                        $travelFontSize = 10;
                                        $maxChars = 52 * $count;
                                    }

                                    // Truncate the destination text if longer than allowed
                                    $text = trim(($list['event_title'] ?? '').(($list['event_title'] ?? null) && ($list['destination'] ?? null) ? ' at ' : '').($list['destination'] ?? ''));
                                    if (strlen($text) > $maxChars) {
                                        $text = substr($text, 0, $maxChars) . '...';
                                    }
                                @endphp
                                @if($isGroupStart['count'] == 1)
                                    <td width="80%" colspan="6" rowspan="{{ $isGroupStart['count'] }}" style="position: relative; vertical-align: middle;font-size: {{ $travelFontSize }}px; color: gray; overflow: visible;">
                                        Official Travel : {{ $text }}
                                    </td>
                                @else
                                    <td width="80%" colspan="6" rowspan="{{ $isGroupStart['count'] }}"
                                    style="position: relative; vertical-align: middle; text-align: center; font-size: {{ $travelFontSize }}px; color: gray; padding-left: 30px; overflow: visible;">

                                        <div style="position: absolute; left: 9px; top: {{ $bracketTop }}px; width: 2px; height: {{ $bracketBarHeight }}px; background-color: gray; pointer-events: none;"></div>
                                        <div style="position: absolute; left: 1px; top: {{ $bracketTop }}px; width: 8px; height: 2px; background-color: gray; pointer-events: none;"></div>
                                        <div style="position: absolute; left: 1px; top: {{ $bracketTop + $bracketBarHeight - 2 }}px; width: 8px; height: 2px; background-color: gray; pointer-events: none;"></div>

                                        Official Travel : {{ $text }}
                                    </td>
                                @endif
                            @elseif($isInGroup)
                                {{-- Skip cell because it's already merged above --}}
                            @else
                                @if($division_id != 35 && $list['data'] == 'HOLIDAY')
                                    <td width="80%" colspan="6" style="text-transform: uppercase; letter-spacing: 2px; font-size: 8px; font-weight: normal; background: rgba(128,128,128, .5)">
                                        {{$list['title']}}
                                    </td>
                                @elseif($division_id != 35 && $list['data'] == 'WORK SUSPENDED')
                                    <td width="80%" colspan="6" style="text-transform: uppercase; letter-spacing: 2px; font-size: 8px; font-weight: normal; background: rgba(128,128,128, .5)">
                                        {{$list['title']}}
                                    </td>
                                @elseif($list['data'] == 'NON-WORKING DAY')
                                <td width="80%" colspan="6" style="letter-spacing: 8px; font-size: 8px; font-weight: normal; background: rgba(214, 214, 214, 0.5)">
                                    {{$list['data']}}
                                </td>
                                @elseif($list['data'] == 'OFFICIAL TRAVEL')
                                <td width="80%" colspan="6" style="position: relative; vertical-align: middle;font-size: {{ strlen($list['destination']) > 58 ? 8 : 10 }}px; color: gray; overflow: visible;">
                                       Official Travel : {{ trim(($list['event_title'] ?? '').(($list['event_title'] ?? null) && ($list['destination'] ?? null) ? ' at ' : '').($list['destination'] ?? '')) }}
                                    </td>
                                @elseif($list['data'] == 'OFFICIAL BUSINESS')
                                <td width="80%" colspan="6" style="position: relative; vertical-align: middle;font-size: {{ strlen($list['title']) > 58 ? 8 : 10 }}px; color: gray; overflow: visible;">
                                    OB : {{ $list['title'] }}
                                </td>
                                @else
                                {{-- <td width="80%" colspan="6" style="letter-spacing: 1px; text-transform: uppercase; font-size: 8px;">
                                    {{ $list['data'] }}
                                </td> --}}
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @endif
                            @endif
                        @else
                            <td>{{ $list['data']['am_in'] ?? '' }}</td>
                            <td>{{ $list['data']['am_out'] ?? '' }}</td>
                            <td>{{ $list['data']['pm_in'] ?? '' }}</td>
                            <td>{{ $list['data']['pm_out'] ?? '' }}</td>
                            <td style="font-size: 9px;">
                                {{-- {{
                                    ($division_id != 35 && $list['data'] && $list['data']['total_minutes'] > 0)
                                        ? ( ($list['data']['undertime']['hours'] == 0) ? '' : $list['data']['undertime']['hours'] )
                                        : ''
                                }} --}}
                              
                                    {{
                                    ($division_id != 35 && $list['data'] && $list['data']['total_minutes'] > 0)
                                        ? ( ($list['data']['undertime']['hours'] == 0) ? '' : $list['data']['undertime']['hours'] )
                                        : ''
                                }}
                            </td>
                            <td style="font-size: 9px;">{{ ($division_id != 35 && $list['data']) ? ($list['data']['total_minutes'] == 0) ? '' : $list['data']['undertime']['minutes'] : '' }}</td>
                        @endif
                </tr>
                @endforeach
                   
                </tbody>
            </table>
            <div style="width: 100%; font-family:Arial, Helvetica, sans-serif">
                <p style="font-size: 10px; margin-top: -2px; margin-left: 9px;">Total : <div style="border-bottom: 1px solid #333; width:86%; margin-top: -8px; margin-left: 40px;"></div></p>
                <p style="font-size: 10px; text-indent: 20px; width: 97%; margin-right: -10px; margin-left: 9px;">I CERTIFY on my honor that the above is a true and correct report of the hours of work peformed, record of which was made daily at the time of arrival at the departure from office.</p>
                <div style="border-bottom: 1px solid #333; width: 50%; float: right; margin-right: 10px; margin-top: 13px;"></div>
                <div style="border-bottom: 4px double #333; width: 94.5%; margin-left: 10px; margin-top: 30px;"></div>
                <center style="font-size: 10px; margin-top: 5px;">Verified as to the prescribed office hours</center>
                <div style="border-bottom: 1px solid #333; width: 50%; float: right; margin-right: 10px; margin-top: 20px;"></div>
                <p style="float: right; font-size: 11px; margin-right: 8px; margin-top: 22px">In Charge</p>
            </div>
        </div>

        @else
        <div style="float: left; width:46%; margin: 0;">
            <div style="font-family:Arial, Helvetica, sans-serif;">
                <p style="font-size: 9px; margin-left: 10px;"><i>CIVIL SERVICE FORM No.48</i></p>
                <h3 style="font-size: 12px; text-align: center;"><u>DAILY TIME RECORD</u></h3>
                <p style="font-size: 11px; text-align: center; margin-top: -7px;">{{ strtoupper($employee['name']) }}</p>
                <div style="border-bottom: 1px solid #333; width: 94%; margin-left: 10px; margin-top: -8px;"></div>
                <div style="font-size: 10px; width: 50%; float: left;">
                    <p style="margin-top: 2px; margin-left: 10px;">For the month of</p>
                    <p style="margin-left: 10px; margin-top: -5px; ">Official hours for arrival and departure</p>
                </div>
                <div style="font-size: 10px; width: 50%; float: right;">
                    <p style="margin-top: 2px; margin-left: 10px;">{{$month}}-{{$year}}</p>
                    <p style="margin-left: 10px; margin-top: -7px; ">Regular days: 8am-5pm</p>
                    <p style="margin-left: 10px; margin-top: -7px; ">Saturdays: not applicable</p>
                </div>
            </div>
            <table style="font-size: 9px; margin-top: 45px; padding: 10px; font-family:Arial, Helvetica, sans-serif">
                <thead>
                    <tr>
                        <td>Days</td>
                        <td colspan="2">A.M</td>
                        <td colspan="2">P.M</td>
                        <td colspan="2">UNDERTIME</td>
                    </tr>
                    <tr>
                        <td style="width: 8%;"></td>
                        <td style="width: 15%;">Arrival</td>
                        <td style="width: 15%;">Departure</td>
                        <td style="width: 15%;">Arrival</td>
                        <td style="width: 15%;">Departure</td>
                        <td style="width: 15%;">Hours</td>
                        <td style="width: 15%;">Minutes</td>
                    </tr>
                </thead>
                <tbody>
                @php
                    $travelGroups = [];
                    $groupCount = 0;
                    $currentTravelGroup = null;
                    $firstHalf = array_slice($lists, 0, 15, true);
                      foreach ($firstHalf as $index => $list) {
                        if (isset($list['data']) && is_string($list['data']) && str_contains($list['data'], 'OFFICIAL TRAVEL')) {
                            if ($groupCount > 0 && ($list['travel_group'] ?? null) !== $currentTravelGroup) {
                                $travelGroups[] = [
                                    'start' => $index - $groupCount,
                                    'count' => $groupCount
                                ];
                                $groupCount = 0;
                            }
                            $currentTravelGroup = $list['travel_group'] ?? null;
                            $groupCount++;
                        } else {
                            if ($groupCount > 0) {
                                $travelGroups[] = [
                                    'start' => $index - $groupCount,
                                    'count' => $groupCount
                                ];
                                $groupCount = 0;
                            }
                            $currentTravelGroup = null;
                        }
                    }
                    if ($groupCount > 0) {
                        $travelGroups[] = [
                            'start' => count($lists) - $groupCount,
                            'count' => $groupCount
                        ];
                    }
                @endphp
                @foreach($lists as $loopIndex => $list)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    @if($loop->iteration <= 15)
                        @if(!$list['is_with'])
                            @php
                                $isGroupStart = collect($travelGroups)->firstWhere('start', $loopIndex);
                                $isInGroup = collect($travelGroups)->contains(function($g) use($loopIndex) {
                                    return $loopIndex >= $g['start'] && $loopIndex < ($g['start'] + $g['count']);
                                });
                            @endphp

                            @if($isGroupStart)
                                @php
                                    $count = $isGroupStart['count'];
                                    $bracketHeight = min(18 * $count, 150);
                                    $bracketRowStep = $bracketHeight / $count;
                                    $bracketTop = $bracketRowStep / 2;
                                    $bracketBarHeight = $bracketHeight - $bracketTop - 8;

                                    if ($count <= 2) {
                                        $travelFontSize = 7;
                                        $maxChars = 60 * $count;
                                    } elseif ($count == 3) {
                                        $travelFontSize = 8;
                                        $maxChars = 100 * $count;
                                    } elseif ($count == 4) {
                                        $travelFontSize = 9;
                                        $maxChars = 130 * $count;
                                    } else {
                                        $travelFontSize = 10;
                                        $maxChars = 52 * $count;
                                    }

                                    // Truncate the destination text if longer than allowed
                                    $text = trim(($list['event_title'] ?? '').(($list['event_title'] ?? null) && ($list['destination'] ?? null) ? ' at ' : '').($list['destination'] ?? ''));
                                    if (strlen($text) > $maxChars) {
                                        $text = substr($text, 0, $maxChars) . '...';
                                    }
                                @endphp
                                @if($isGroupStart['count'] == 1)
                                    <td width="80%" colspan="6" rowspan="{{ $isGroupStart['count'] }}" style="position: relative; vertical-align: middle;font-size: {{ $travelFontSize }}px; color: gray; overflow: visible;">
                                        Official Travel : {{ $text }}
                                    </td>
                                @else
                                    <td width="80%" colspan="6" rowspan="{{ $isGroupStart['count'] }}"
                                    style="position: relative; vertical-align: middle; text-align: center; font-size: {{ $travelFontSize }}px; color: gray; padding-left: 30px; overflow: visible;">

                                        <div style="position: absolute; left: 9px; top: {{ $bracketTop }}px; width: 2px; height: {{ $bracketBarHeight }}px; background-color: gray; pointer-events: none;"></div>
                                        <div style="position: absolute; left: 1px; top: {{ $bracketTop }}px; width: 8px; height: 2px; background-color: gray; pointer-events: none;"></div>
                                        <div style="position: absolute; left: 1px; top: {{ $bracketTop + $bracketBarHeight - 2 }}px; width: 8px; height: 2px; background-color: gray; pointer-events: none;"></div>

                                        Official Travel : {{ $text }}
                                    </td>
                                @endif
                            @elseif($isInGroup)
                                {{-- Skip cell because it's already merged above --}}
                            @else
                                @if($division_id != 35 && $list['data'] == 'HOLIDAY')
                                    <td width="80%" colspan="6" style="text-transform: uppercase; letter-spacing: 2px; font-size: 8px; font-weight: normal; background: rgba(128,128,128, .5)">
                                        {{$list['title']}}
                                    </td>
                                @elseif($division_id != 35 && $list['data'] == 'WORK SUSPENDED')
                                    <td width="80%" colspan="6" style="text-transform: uppercase; letter-spacing: 2px; font-size: 8px; font-weight: normal; background: rgba(128,128,128, .5)">
                                        {{$list['title']}}
                                    </td>
                                @elseif($list['data'] == 'NON-WORKING DAY')
                                <td width="80%" colspan="6" style="letter-spacing: 8px; font-size: 8px; font-weight: normal; background: rgba(214, 214, 214, 0.5)">
                                    {{$list['data']}}
                                </td>
                                @elseif($list['data'] == 'OFFICIAL TRAVEL')
                                <td width="80%" colspan="6" style="position: relative; vertical-align: middle;font-size: {{ strlen($list['destination']) > 58 ? 8 : 10 }}px; color: gray; overflow: visible;">
                                       Official Travel : {{ trim(($list['event_title'] ?? '').(($list['event_title'] ?? null) && ($list['destination'] ?? null) ? ' at ' : '').($list['destination'] ?? '')) }}
                                    </td>
                                @elseif($list['data'] == 'OFFICIAL BUSINESS')
                                <td width="80%" colspan="6" style="position: relative; vertical-align: middle;font-size: {{ strlen($list['title']) > 58 ? 8 : 10 }}px; color: gray; overflow: visible;">
                                    OB : {{ $list['title'] }}
                                </td>
                                @else
                                {{-- <td width="80%" colspan="6" style="letter-spacing: 1px; text-transform: uppercase; font-size: 8px;">
                                    {{ $list['data'] }}
                                </td> --}}
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @endif
                            @endif
                        @else
                            <td style="font-size: 9px;">{{ ($list['data']) ? $list['data']['am_in'] : '' }}</td>
                            <td style="font-size: 9px;">{{ ($list['data']) ? $list['data']['am_out'] : '' }}</td>
                            <td style="font-size: 9px;">{{ ($list['data']) ? $list['data']['pm_in'] : '' }}</td>
                            <td style="font-size: 9px;">{{ ($list['data']) ? $list['data']['pm_out'] : '' }}</td>
                            <td style="font-size: 9px;">
                                {{
                                    ($division_id != 35 && $list['data'] && $list['data']['total_minutes'] > 0)
                                        ? ( ($list['data']['undertime']['hours'] == 0) ? '' : $list['data']['undertime']['hours'] )
                                        : ''
                                }}
                            </td>
                            <td style="font-size: 9px;">{{ ($division_id != 35 && $list['data']) ? ($list['data']['total_minutes'] == 0) ? '' : $list['data']['undertime']['minutes'] : '' }}</td>
                        @endif
                    @else 
                        <td style="background: rgba(128,128,128, .5)"></td>
                        <td style="background: rgba(128,128,128, .5)"></td>
                        <td style="background: rgba(128,128,128, .5)"></td>
                        <td style="background: rgba(128,128,128, .5)"></td>
                        <td style="background: rgba(128,128,128, .5)"></td>
                        <td style="background: rgba(128,128,128, .5)"></td>
                    @endif
                </tr>
                @endforeach
                   
                </tbody>
            </table>
            <div style="width: 100%; font-family:Arial, Helvetica, sans-serif">
                <p style="font-size: 10px; margin-top: -2px; margin-left: 9px;">Total : <div style="border-bottom: 1px solid #333; width:86%; margin-top: -8px; margin-left: 40px;"></div></p>
                <p style="font-size: 10px; text-indent: 20px; width: 97%; margin-right: -10px; margin-left: 9px;">I CERTIFY on my honor that the above is a true and correct report of the hours of work peformed, record of which was made daily at the time of arrival at the departure from office.</p>
                <div style="border-bottom: 1px solid #333; width: 50%; float: right; margin-right: 10px; margin-top: 13px;"></div>
                <div style="border-bottom: 4px double #333; width: 94.5%; margin-left: 10px; margin-top: 30px;"></div>
                <center style="font-size: 10px; margin-top: 5px;">Verified as to the prescribed office hours</center>
                <div style="border-bottom: 1px solid #333; width: 50%; float: right; margin-right: 10px; margin-top: 20px;"></div>
                <p style="float: right; font-size: 11px; margin-right: 8px; margin-top: 22px">In Charge</p>
            </div>
        </div>
        <div style="float: right; width:46%; margin: 0;">
            <div style="font-family:Arial, Helvetica, sans-serif;">
                <p style="font-size: 9px; margin-left: 10px;"><i>CIVIL SERVICE FORM No.48</i></p>
                <h3 style="font-size: 12px; text-align: center;"><u>DAILY TIME RECORD</u></h3>
                <p style="font-size: 11px; text-align: center; margin-top: -7px;">{{ strtoupper($employee['name']) }}</p>
                <div style="border-bottom: 1px solid #333; width: 94%; margin-left: 10px; margin-top: -8px;"></div>
                <div style="font-size: 10px; width: 50%; float: left;">
                    <p style="margin-top: 2px; margin-left: 10px;">For the month of</p>
                    <p style="margin-left: 10px; margin-top: -5px; ">Official hours for arrival and departure</p>
                </div>
                <div style="font-size: 10px; width: 50%; float: right;">
                    <p style="margin-top: 2px; margin-left: 10px;">{{$month}}-{{$year}}</p>
                    <p style="margin-left: 10px; margin-top: -7px; ">Regular days: 8am-5pm</p>
                    <p style="margin-left: 10px; margin-top: -7px; ">Saturdays: not applicable</p>
                </div>
            </div>
            <table style="font-size: 9px; margin-top: 45px; padding: 10px; font-family:Arial, Helvetica, sans-serif">
                <thead>
                            <tr>
                        <td>Days</td>
                        <td colspan="2">A.M</td>
                        <td colspan="2">P.M</td>
                        <td colspan="2">UNDERTIME</td>
                    </tr>
                    <tr>
                        <td style="width: 8%;"></td>
                        <td style="width: 15%;">Arrival</td>
                        <td style="width: 15%;">Departure</td>
                        <td style="width: 15%;">Arrival</td>
                        <td style="width: 15%;">Departure</td>
                        <td style="width: 15%;">Hours</td>
                        <td style="width: 15%;">Minutes</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $travelGroups = [];
                        $groupCount = 0;
                        $currentTravelGroup = null;
                        $secondHalf = array_slice($lists, 15, null, true);
                          foreach ($secondHalf as $index => $list) {
                            if (isset($list['data']) && is_string($list['data']) && str_contains($list['data'], 'OFFICIAL TRAVEL')) {
                                if ($groupCount > 0 && ($list['travel_group'] ?? null) !== $currentTravelGroup) {
                                    $travelGroups[] = [
                                        'start' => $index - $groupCount,
                                        'count' => $groupCount
                                    ];
                                    $groupCount = 0;
                                }
                                $currentTravelGroup = $list['travel_group'] ?? null;
                                $groupCount++;
                            } else {
                                if ($groupCount > 0) {
                                    $travelGroups[] = [
                                        'start' => $index - $groupCount,
                                        'count' => $groupCount
                                    ];
                                    $groupCount = 0;
                                }
                                $currentTravelGroup = null;
                            }
                        }
                        if ($groupCount > 0) {
                            $travelGroups[] = [
                                'start' => count($lists) - $groupCount,
                                'count' => $groupCount
                            ];
                        }
                    @endphp

                    @foreach($lists as $loopIndex => $list)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>

                        @if($loop->iteration > 15)
                            @if(!$list['is_with'])
                                @php
                                    $isGroupStart = collect($travelGroups)->firstWhere('start', $loopIndex);
                                    $isInGroup = collect($travelGroups)->contains(function($g) use($loopIndex) {
                                        return $loopIndex >= $g['start'] && $loopIndex < ($g['start'] + $g['count']);
                                    });
                                @endphp

                                @if($isGroupStart)
                                    @php
                                        $count = $isGroupStart['count'];
                                        $bracketHeight = min(18 * $count, 150);
                                    $bracketRowStep = $bracketHeight / $count;
                                    $bracketTop = $bracketRowStep / 2;
                                    $bracketBarHeight = $bracketHeight - $bracketTop - 8;

                                        if ($count <= 2) {
                                        $travelFontSize = 7;
                                        $maxChars = 60 * $count;
                                    } elseif ($count == 3) {
                                        $travelFontSize = 8;
                                        $maxChars = 100 * $count;
                                    } elseif ($count == 4) {
                                        $travelFontSize = 9;
                                        $maxChars = 130 * $count;
                                    } else {
                                        $travelFontSize = 10;
                                        $maxChars = 52 * $count;
                                    }

                                        // Truncate the destination text if longer than allowed
                                         $text = trim(($list['event_title'] ?? '').(($list['event_title'] ?? null) && ($list['destination'] ?? null) ? ' at ' : '').($list['destination'] ?? ''));
                                        if (strlen($text) > $maxChars) {
                                            $text = substr($text, 0, $maxChars) . '...';
                                        }
                                    @endphp

                                   @if($isGroupStart['count'] == 1)
                                        <td width="80%" colspan="6" rowspan="{{ $isGroupStart['count'] }}" style="position: relative; vertical-align: middle;font-size: {{ $travelFontSize }}px; color: gray; overflow: visible;">
                                            Official Travel : {{ $text }}
                                        </td>
                                    @else
                                        <td width="80%" colspan="6" rowspan="{{ $isGroupStart['count'] }}"
                                        style="position: relative; vertical-align: middle; text-align: center; font-size: {{ $travelFontSize }}px; color: gray; padding-left: 30px; overflow: visible;">

                                            <div style="position: absolute; left: 9px; top: {{ $bracketTop }}px; width: 2px; height: {{ $bracketBarHeight }}px; background-color: gray; pointer-events: none;"></div>
                                            <div style="position: absolute; left: 1px; top: {{ $bracketTop }}px; width: 8px; height: 2px; background-color: gray; pointer-events: none;"></div>
                                            <div style="position: absolute; left: 1px; top: {{ $bracketTop + $bracketBarHeight - 2 }}px; width: 8px; height: 2px; background-color: gray; pointer-events: none;"></div>

                                            Official Travel : {{ $text }}
                                        </td>
                                    @endif
                                @elseif($isInGroup)
                                    {{-- Skip cell because it's already merged above --}}
                                @else
                                    @if($division_id != 35 && $list['data'] == 'HOLIDAY')
                                        <td width="80%" colspan="6" style="text-transform: uppercase; letter-spacing: 2px; font-size: 8px; font-weight: normal; background: rgba(128,128,128, .5)">
                                            {{$list['title']}}
                                        </td>
                                    @elseif($division_id != 35 && $list['data'] == 'WORK SUSPENDED')
                                        <td width="80%" colspan="6" style="text-transform: uppercase; letter-spacing: 2px; font-size: 8px; font-weight: normal; background: rgba(128,128,128, .5)">
                                            {{$list['title']}}
                                        </td>
                                    @elseif($list['data'] == 'NON-WORKING DAY')
                                    <td width="80%" colspan="6" style="letter-spacing: 8px; font-size: 8px; font-weight: normal; background: rgba(214, 214, 214, 0.5)">
                                        {{$list['data']}}
                                    </td>
                                    @elseif($list['data'] == 'OFFICIAL TRAVEL')
                                    <td width="80%" colspan="6" style="position: relative; vertical-align: middle;font-size: {{ strlen($list['destination']) > 58 ? 8 : 10 }}px; color: gray; overflow: visible;">
                                        Official Travel : {{ trim(($list['event_title'] ?? '').(($list['event_title'] ?? null) && ($list['destination'] ?? null) ? ' at ' : '').($list['destination'] ?? '')) }}
                                    </td>
                                    @elseif($list['data'] == 'OFFICIAL BUSINESS')
                                    <td width="80%" colspan="6" style="position: relative; vertical-align: middle;font-size: {{ strlen($list['title']) > 58 ? 8 : 10 }}px; color: gray; overflow: visible;">
                                        OB : {{ $list['title'] }}
                                    </td>
                                    @else
                                    {{-- <td width="80%" colspan="6" style="letter-spacing: 1px; text-transform: uppercase; font-size: 8px; background: rgba(128,128,128, .5)">
                                        {{ $list['data'] }}
                                    </td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @endif
                                @endif
                            @else
                                <td style="font-size: 9px;">{{ $list['data']['am_in'] ?? '' }}</td>
                                <td style="font-size: 9px;">{{ $list['data']['am_out'] ?? '' }}</td>
                                <td style="font-size: 9px;">{{ $list['data']['pm_in'] ?? '' }}</td>
                                <td style="font-size: 9px;">{{ $list['data']['pm_out'] ?? '' }}</td>
                                <td style="font-size: 9px;">
                                    {{
                                        ($division_id != 35 && $list['data'] && $list['data']['total_minutes'] > 0)
                                            ? ( ($list['data']['undertime']['hours'] == 0) ? '' : $list['data']['undertime']['hours'] )
                                            : ''
                                    }}
                                </td>
                                <td style="font-size: 9px;">{{ ($division_id != 35 && $list['data']) ? ($list['data']['total_minutes'] == 0) ? '' : $list['data']['undertime']['minutes'] : '' }}</td>
                            @endif
                        @else 
                            <td style="background: rgba(128,128,128, .5)"></td>
                            <td style="background: rgba(128,128,128, .5)"></td>
                            <td style="background: rgba(128,128,128, .5)"></td>
                            <td style="background: rgba(128,128,128, .5)"></td>
                            <td style="background: rgba(128,128,128, .5)"></td>
                            <td style="background: rgba(128,128,128, .5)"></td>
                        @endif
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
            <div style="width: 100%; font-family:Arial, Helvetica, sans-serif">
                <p style="font-size: 10px; margin-top: -2px; margin-left: 9px;">Total : <div style="border-bottom: 1px solid #333; width:86%; margin-top: -8px; margin-left: 40px;"></div></p>
                <p style="font-size: 10px; text-indent: 20px; width: 97%; margin-right: -10px; margin-left: 9px;">I CERTIFY on my honor that the above is a true and correct report of the hours of work peformed, record of which was made daily at the time of arrival at the departure from office.</p>
                <div style="border-bottom: 1px solid #333; width: 50%; float: right; margin-right: 10px; margin-top: 13px;"></div>
                <div style="border-bottom: 4px double #333; width: 94.5%; margin-left: 10px; margin-top: 30px;"></div>
                <center style="font-size: 10px; margin-top: 5px;">Verified as to the prescribed office hours</center>
                <div style="border-bottom: 1px solid #333; width: 50%; float: right; margin-right: 10px; margin-top: 20px;"></div>
                <p style="float: right; font-size: 11px; margin-right: 8px; margin-top: 22px">In Charge</p>
            </div>
        </div>
        @endif
    </body>
</html>
<style>
    .bracket {
    position: relative;
}
.bracket::before {
    content: "";
    position: absolute;
    left: -5px; /* adjust horizontal position */
    top: 0;
    bottom: 0;
    width: 2px;
    border-left: 1px solid black; /* thickness and color of bracket */
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    border-radius: 10px; /* curve the bracket ends */
}</style>