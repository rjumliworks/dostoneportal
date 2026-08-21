<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CS Form No. 212 - Personal Data Sheet</title>
    <style>
        /* dompdf: no flexbox, no box-shadow, limited @page support (one physical
           size for the whole document, which is fine since every page here is
           the same legal size) — layout is table-based throughout. */
        @page { margin: 0.2in 0.3in; }

        * { font-family: Arial, Helvetica, sans-serif; color: #000; }
        body { margin: 0; padding: 0; }

        table { width: 100%; border-collapse: collapse;  table-layout: fixed; font-size: 7.5pt; }
        th, td { border: 0.75px solid #000; padding: 5.5px 4px; vertical-align: middle; word-wrap: break-word; }

        .header-table, .header-table td { border: none; padding: 0; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-justify { text-align: justify; }
        .text-bold { font-weight: bold; }
        .text-italic { font-style: italic; }
        .text-xs { font-size: 6pt; }
        .text-sm { font-size: 7.5pt; }
        .text-md { font-size: 9pt; }
        .text-lg { font-size: 22pt; font-weight: 900; letter-spacing: -0.5px; }

        .bg-gray { background-color: #eaeaea; }
        .bg-dark-gray { background-color: #999999; color: #fff; }

        .section-header {
            background-color: #999999; color: #fff;
            font-style: italic; font-weight: bold; font-size: 9.5pt;
            padding: 2px 5px; border: 1.5px solid #000; border-bottom: none;
        }

        .no-border { border: none; }

        .chk {
            display: inline-block; width: 9px; height: 9px;
            border: 0.75px solid #000; text-align: center;
            line-height: 8px; font-size: 8pt; font-weight: bold;
        }

       .page-footer {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .page-footer td {
            height: 24px;
            padding: 3px 5px;
            background-color: #eaeaea;
            vertical-align: middle;
            font-size: 7pt;
        }

        .page-footer .signature-label {
            width: 15%;
            text-align: center;
            font-weight: bold;
            font-style: italic;
        }

        .page-footer .signature-note {
            width: 42%;
            text-align: center;
            color: #ff0000;
            font-size: 5.5pt;
            font-weight: normal;
            font-style: normal;
        }

        .page-footer .date-label {
            width: 12%;
            text-align: center;
            font-weight: bold;
            font-style: italic;
        }

        .page-footer .date-value {
            width: 21%;
            text-align: center;
            font-weight: bold;
            font-style: normal;
        }

        .footer-reference {
            text-align: right;
            font-size: 5.5pt;
            font-weight: bold;
            font-style: italic;
            margin-top: 2px;
        }

        .warning-text { font-size: 7.5pt; font-weight: bold; font-style: italic; text-align: justify; margin: 3px 0; }
        .guide-text { font-size: 7pt; font-weight: bold; }

        .page-break { page-break-after: always; }
        .foot { text-align: right; font-size: 6.5pt; font-style: italic; margin-top: 1px; }
        .cont { text-align: center; font-style: italic; font-weight: bold; font-size: 6pt; color: #c00000; }
        .page2-table td {
            text-align: center;
            vertical-align: middle;
        }


    </style>
</head>
<body>

@php
    // {{ }} auto-escapes, so these helpers must never pre-escape their output.
    $v  = fn($x) => ($x === null || $x === '') ? 'N/A' : $x;
    $vb = fn($x) => ($x === null || $x === '') ? '' : $x;
    // Raw HTML — always echoed with {!! !!}.
    $chk = fn($on) => '<span class="chk">' . ($on ? 'X' : '') . '</span>';

    $sig = $declaration?->declared_at ? \Carbon\Carbon::parse($declaration->declared_at)->format('F d, Y') : now()->format('F d, Y');

    $children = collect($backgrounds['children'] ?? []);
    $spouse   = $backgrounds['spouse'] ?? [];
    $father   = $backgrounds['parents']['father'] ?? [];
    $mother   = $backgrounds['parents']['mother'] ?? [];

    $cz = $personal['citizenship'] ?? null;
    $ms = $profile->marital->name ?? null;
    $sx = $profile->sex->name ?? null;

    // The paper form splits an address into House/Lot+Street, Subdivision+Barangay,
    // City/Municipality+Province rows — our schema only keeps one free-text `address`
    // line plus the barangay/municipality/province/zip relations, so the full text
    // goes on the first row and the rest map onto their matching sub-fields.
    $addressBlock = function ($a) {
        if (!$a) {
            return '<div class="text-center">N/A</div><div class="text-center text-xs text-italic">House/Block/Lot No. &amp; Street</div>'
                 . '<div class="text-center">N/A</div><div class="text-center text-xs text-italic">Subdivision/Village &amp; Barangay</div>'
                 . '<div class="text-center">N/A</div><div class="text-center text-xs text-italic">City/Municipality &amp; Province</div>';
        }
        $addr = e($a->address ?? 'N/A');
        $brgy = e($a->barangay->name ?? 'N/A');
        $muni = e($a->municipality->name ?? 'N/A');
        $prov = e($a->province->name ?? 'N/A');
        return "<div class=\"text-center\">{$addr}</div><div class=\"text-center text-xs text-italic\">House/Block/Lot No. &amp; Street</div>"
             . "<div class=\"text-center\">{$brgy}</div><div class=\"text-center text-xs text-italic\">Subdivision/Village &amp; Barangay</div>"
             . "<div class=\"text-center\">{$muni} &nbsp;&nbsp; {$prov}</div><div class=\"text-center text-xs text-italic\">City/Municipality &amp; Province</div>";
    };


    function fitFont($text, $normal = 10)
        {
            $length = strlen($text ?? '');

            if ($length > 75) {
                return 7;
            }

            if ($length <= 75) {
                return 9;
            }

            return $normal;
        }

    // Normalizes "Not Available" / "n/a" (any case) to a greyed-out "N/A" so
    // placeholder values read consistently no matter how they were entered.
    function naText($text)
        {
            $t = trim((string) ($text ?? ''));
            if ($t === '' || in_array(strtolower($t), ['n/a', 'na', 'not available'], true)) {
                return '<span style="color:#999;">N/A</span>';
            }
            return e($t);
        }
@endphp

{{-- ============================================================ PAGE 1 ============================================================ --}}
<table class="header-table">
    <tr>
        <td style="width:20%;" class="text-xs text-italic text-bold">CS Form No. 212<br>Revised 2026</td>
        <td style="width:60%; text-align:center;" class="text-lg text-bold">PERSONAL DATA SHEET</td>
        <td style="width:20%;"></td>
    </tr>
</table>

<div class="warning-text">WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.</div>
<div class="guide-text">READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.</div>
<div class="text-xs" style="margin-bottom:2px;">Print legibly. Tick appropriate boxes <span class="chk"></span> and use separate sheet if necessary. Indicate N/A if not applicable. <b>DO NOT ABBREVIATE.</b></div>

<div class="section-header">I. PERSONAL INFORMATION</div>
<table style="border-top:none;">
    <tr>
        <td class="bg-gray text-xs" style="width:18%;">1. SURNAME</td>
        <td colspan="3" class="text-bold">{{ $v($profile->lastname) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">2. FIRST NAME</td>
        <td style="width:45%;" class="text-bold">{{ $v($profile->firstname) }}</td>
        <td class="bg-gray text-xs" style="width:17%;">NAME EXTENSION (JR., SR)</td>
        <td style="width:20%;" class="text-bold">{{ $v($profile->suffix->name ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">MIDDLE NAME</td>
        <td colspan="3" class="text-bold">{{ $v($profile->middlename) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">3. DATE OF BIRTH<br>(mm/dd/yyyy)</td>
        <td class="text-bold">{{ $v($profile->birthdate) }}</td>
        <td class="bg-gray text-xs" rowspan="3" style="vertical-align:top;">16. CITIZENSHIP<br><br><span class="text-xs text-italic">If holder of dual citizenship,<br>please indicate the details.</span></td>
        <td rowspan="3" style="vertical-align:top; font-size:8pt;">
            <div style="margin-bottom:2px;">{!! $chk($cz === 'filipino') !!} Filipino &nbsp;&nbsp; {!! $chk($cz === 'dual') !!} Dual Citizenship</div>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! $chk(($personal['citizenship_type'] ?? null) === 'by_birth') !!} by birth &nbsp;&nbsp; {!! $chk(($personal['citizenship_type'] ?? null) === 'by_naturalization') !!} by naturalization</div>
            <div style="font-size:7pt; margin-top:5px; text-align:center;">Pls. indicate country:</div>
            <div style="border-bottom:0.75px solid #000; height:12px; width:90%; margin:0 auto; text-align:center; font-size:7pt;">{{ $vb($personal['citizenship_country'] ?? null) }}</div>
        </td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">4. PLACE OF BIRTH</td>
        <td class="text-bold">{{ $v($personal['place_of_birth'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">5. SEX AT BIRTH</td>
        <td>{!! $chk($sx === 'Male') !!} Male &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($sx === 'Female') !!} Female</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs" rowspan="2">6. CIVIL STATUS</td>
        <td rowspan="2" style="vertical-align:top;">
            {!! $chk($ms === 'Single') !!} Single &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($ms === 'Married') !!} Married<br>
            {!! $chk($ms === 'Widowed') !!} Widowed &nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($ms === 'Separated') !!} Separated<br>
            {!! $chk($ms && !in_array($ms, ['Single','Married','Widowed','Separated'])) !!} Other/s:
            <span style="display:inline-block; border-bottom:0.75px solid #000; width:100px; text-align:center;">{{ ($ms && !in_array($ms, ['Single','Married','Widowed','Separated'])) ? $ms : '' }}</span>
        </td>
        <td class="bg-gray text-xs" rowspan="3" style="vertical-align:top;">17. RESIDENTIAL ADDRESS</td>
        <td>{!! $addressBlock($residential) !!}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">7. HEIGHT (m)</td>
        <td class="text-bold">{{ $v($personal['height'] ?? null) }}</td>
        <td>
            <div class="text-center">{{ $vb($residential?->zip_code) ?: 'N/A' }}</div>
            <div class="text-center text-xs text-italic">ZIP CODE</div>
        </td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">8. WEIGHT (kg)</td>
        <td class="text-bold">{{ $v($personal['weight'] ?? null) }}</td>
        <td class="bg-gray text-xs" rowspan="3" style="vertical-align:top;">18. PERMANENT ADDRESS</td>
        <td rowspan="3">{!! $addressBlock($permanent) !!}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">9. BLOOD TYPE</td>
        <td class="text-bold">{{ $v($profile->blood->name ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">10. UMID ID NO.</td>
        <td class="text-bold">{{ $v($accounts['UMID']['number'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">11. PAG-IBIG ID NO.</td>
        <td class="text-bold">{{ $v($accounts['Pag-Ibig']['number'] ?? null) }}</td>
        <td>
            <div class="text-center">{{ $vb($permanent?->zip_code) ?: 'N/A' }}</div>
            <div class="text-center text-xs text-italic">ZIP CODE</div>
        </td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">12. PHILHEALTH NO.</td>
        <td class="text-bold">{{ $v($accounts['PhilHealth']['number'] ?? null) }}</td>
        <td class="bg-gray text-xs">19. TELEPHONE NO.</td>
        <td class="text-bold">N/A</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">13. PhilSys Number (PSN)</td>
        <td class="text-bold">{{ $v($accounts['PhilSys']['number'] ?? null) }}</td>
        <td class="bg-gray text-xs">20. MOBILE NO.</td>
        <td class="text-bold">{{ $v($profile->mobile) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">14. TIN NO.</td>
        <td class="text-bold">{{ $v($accounts['TIN']['number'] ?? null) }}</td>
        <td class="bg-gray text-xs">21. E-MAIL ADDRESS (if any)</td>
        <td class="text-bold" style="color:#0000cc;">{{ $v($user->email) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">15. AGENCY EMPLOYEE NO.</td>
        <td class="text-bold" colspan="3">{{ $v($personal['agency_employee_no'] ?? null) }}</td>
    </tr>
</table>

<div class="section-header">II. FAMILY BACKGROUND</div>
<table style="border-top:none;">
    <tr>
        <td class="bg-gray text-xs" style="width:18%;">22. SPOUSE'S SURNAME</td>
        <td style="width:42%;" class="text-bold">{{ $v($spouse['lastname'] ?? null) }}</td>
        <td class="bg-gray text-xs" style="width:25%;">23. NAME of CHILDREN <span class="text-italic">(Write full name)</span></td>
        <td class="bg-gray text-xs text-center" style="width:15%;">DATE OF BIRTH (mm/dd/yyyy)</td>
    </tr>
    @php $childRows = max(4, $children->count()); @endphp
    <tr>
        <td class="bg-gray text-xs">FIRST NAME</td>
        <td class="text-bold">{{ $v($spouse['firstname'] ?? null) }} <span class="text-xs text-italic">(Ext: {{ $vb($spouse['suffix'] ?? null) ?: 'N/A' }})</span></td>
        <td rowspan="{{ $childRows }}" colspan="2" style="vertical-align:top; padding:0;">
            <table class="no-border" style="border:none;">
                @forelse($children as $child)
                    <tr>
                        <td style="width:70%; border-left:none; border-top:none;">{{ $child['name'] ?? 'N/A' }}</td>
                        <td class="text-center" style="width:30%; border-right:none; border-top:none;">{{ $child['birthdate'] ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr><td style="border-left:none; border-top:none;">N/A</td><td class="text-center" style="border-right:none; border-top:none;">N/A</td></tr>
                @endforelse
                @for($i = $children->count(); $i < $childRows - 1; $i++)
                    <tr><td style="border-left:none; border-top:none;">&nbsp;</td><td style="border-right:none; border-top:none;">&nbsp;</td></tr>
                @endfor
            </table>
        </td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">MIDDLE NAME</td>
        <td class="text-bold">{{ $v($spouse['middlename'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">OCCUPATION</td>
        <td class="text-bold">{{ $v($spouse['occupation'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">EMPLOYER/BUSINESS NAME</td>
        <td class="text-bold">{{ $v($spouse['company'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">BUSINESS ADDRESS</td>
        <td class="text-bold">{{ $v($spouse['address'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">TELEPHONE NO.</td>
        <td class="text-bold" colspan="3">{{ $v($spouse['contact_no'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">24. FATHER'S SURNAME</td>
        <td class="text-bold" colspan="3">{{ $v($father['lastname'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">FIRST NAME</td>
        <td class="text-bold" colspan="3">{{ $v($father['firstname'] ?? null) }} <span class="text-xs text-italic">(Ext: {{ $vb($father['suffix'] ?? null) ?: 'N/A' }})</span></td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">MIDDLE NAME</td>
        <td class="text-bold" colspan="3">{{ $v($father['middlename'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">25. MOTHER'S MAIDEN NAME</td>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">SURNAME</td>
        <td class="text-bold" colspan="3">{{ $v($mother['lastname'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">FIRST NAME</td>
        <td class="text-bold" colspan="3">{{ $v($mother['firstname'] ?? null) }}</td>
    </tr>
    <tr>
        <td class="bg-gray text-xs">MIDDLE NAME</td>
        <td class="text-bold">{{ $v($mother['middlename'] ?? null) }}</td>
        <td colspan="2" class="cont">(Continue on separate sheet if necessary)</td>
    </tr>
</table>

<div class="section-header">III. EDUCATIONAL BACKGROUND</div>
<table style="border-top:none;">
    <tr class="bg-gray text-center text-xs">
        <td rowspan="2" style="width:12%;">26.<br>LEVEL</td>
        <td rowspan="2" style="width:22%;">NAME OF SCHOOL<br>(Write in full)</td>
        <td rowspan="2" style="width:19%;">BASIC EDUCATION/DEGREE/COURSE<br>(Write in full)</td>
        <td colspan="2" style="width:14%;">PERIOD OF ATTENDANCE</td>
        <td rowspan="2" style="width:11%;">HIGHEST LEVEL/UNITS EARNED<br>(if not graduated)</td>
        <td rowspan="2" style="width:8%;">YEAR<br>GRADUATED</td>
        <td rowspan="2" style="width:14%;">SCHOLARSHIP/<br>ACADEMIC HONORS</td>
    </tr>
    <tr class="bg-gray text-center text-xs">
        <td style="width:7%;">From</td>
        <td style="width:7%;">To</td>
    </tr>
     @php
        function fitFont6($text, $normal = 10)
        {
            $length = strlen($text ?? '');
            if ($length > 30) {
                return 9;
            }
            return $normal;
        }
        function fitFont7($text, $normal = 10)
        {
            $length = strlen($text ?? '');
            if ($length > 30) {
                return 8;
            }
            return $normal;
        }
    @endphp
    @foreach($education as $levelName => $rows)
    @php
        $longestSchool = $rows->map(fn($r) => $r->school->name ?? '')->sortByDesc(fn($n) => strlen($n))->first() ?? '';
        $longestCourse = $rows->map(fn($r) => $r->course->name ?? '')->sortByDesc(fn($n) => strlen($n))->first() ?? '';
    @endphp
    <tr style="height:24px;">
        <td class="bg-gray text-xs" style="vertical-align:middle;">{{ $levelName }}</td>
        <td class="text-center" style="font-size: {{ fitFont6($longestSchool) }}px;">@forelse($rows as $r){!! naText($r->school->name ?? 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
        <td class="text-center" style="font-size: {{ fitFont7($longestCourse) }}px;">@forelse($rows as $r){!! naText($r->course->name ?? 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
        <td class="text-center">@forelse($rows as $r){!! naText($r->attended_from ?? 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
        <td class="text-center">@forelse($rows as $r){!! naText($r->attended_to ?? 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
        <td class="text-center">@forelse($rows as $r){!! naText($r->units_earned ?: 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
        <td class="text-center">@forelse($rows as $r){!! $r->is_ongoing ? 'Ongoing' : naText($r->graduated_at ?? 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
        <td class="text-center">@forelse($rows as $r){!! naText($r->honors ?: 'N/A') !!}@if(!$loop->last)<br>@endif @empty {!! naText('N/A') !!} @endforelse</td>
    </tr>
    @endforeach
    <tr><td colspan="8" class="cont">(Continue on separate sheet if necessary)</td></tr>
</table>

<table class="page-footer">
    <tr>
        <td class="signature-label">
            SIGNATURE
        </td>

        <td class="signature-note">
            (wet signature/e-signature/digital certificate)
        </td>

        <td class="date-label">
            DATE
        </td>

        <td class="date-value">
            {{ $sig }}
        </td>
    </tr>
</table>

<div class="footer-reference">
    CS FORM 212 (Revised 2025), Page 1 of 4
</div>

<div class="page-break"></div>

{{-- ============================================================ PAGE 2 ============================================================ --}}
<div class="section-header">IV. CIVIL SERVICE ELIGIBILITY</div>
<table style="border-top:none;" class="page2-table">
    <tr class="bg-gray text-center text-xs">
        <td rowspan="2" style="width:35%;">27. CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER SPECIAL LAWS/ CES/ CSEE<br>ELIGIBILITY FOR UNIFORMED PERSONNEL</td>
        <td rowspan="2" style="width:10%;">RATING<br>(If Applicable)</td>
        <td rowspan="2" style="width:15%;">DATE OF EXAMINATION /<br>CONFERMENT</td>
        <td rowspan="2" style="width:25%;">PLACE OF EXAMINATION /<br>CONFERMENT</td>
        <td colspan="2" style="width:15%;">LICENSE (if applicable)</td>
    </tr>
    <tr class="bg-gray text-center text-xs">
        <td>NUMBER</td>
        <td>Valid Until</td>
    </tr>
    @for($i = 0; $i < 7; $i++)
    @php $e = $eligibilities[$i] ?? null; @endphp
    <tr>
        <td style="height: 25px; max-height: 25px;">
            <div style="font-size: {{ fitFont($e->exam_name ?? '') }}px;">{{ $e->exam_name ?? '' }}</div>
        </td>
        <td><div>{{ $e->rating ?? '' }} </div></td>
        <td><div>{{ $e->exam_at ?? '' }}</div></td>
        <td>
            <div style="font-size: {{ fitFont($e->exam_place ?? '') }}px;">{{ $e->exam_place ?? '' }}</div>
        </td>
        <td>
            <div>{{ $e ? ($e->license_number ?: 'N/A') : '' }}</div>
        </td>
        <td>
            <div> {{ $e ? ($e->license_valid_until ?: 'N/A') : '' }}</div>
        </td>
    </tr>
    @endfor
    <tr>
        <td colspan="6" class="cont">(Continue on separate sheet if necessary)</td>
    </tr>
</table>

<div class="section-header">V. WORK EXPERIENCE</div>
<table style="border-top:none; margin-bottom:0;">
    <tr><td class="bg-gray text-xs text-italic text-bold">(Include private employment. Start from your recent work.) Description of duties should be indicated in the attached Work Experience Sheet.</td></tr>
</table>
<table style="border-top:none;">
    <tr class="bg-gray text-center text-xs">
        <td colspan="2" style="width:10%;">28. INCLUSIVE DATES<br>(mm/dd/yyyy)</td>
        <td rowspan="2" style="width:20%;">POSITION TITLE<br>(Write in full/Do not abbreviate)</td>
        <td rowspan="2" style="width:26%;">DEPARTMENT / AGENCY / OFFICE / COMPANY<br>(Write in full/Do not abbreviate)</td>
        <td rowspan="2" style="width:11%;">MONTHLY<br>SALARY</td>
        <td rowspan="2" style="width:8%;">SALARY/JOB/<br>PAY GRADE</td>
        <td rowspan="2" style="width:12%;">STATUS OF<br>APPOINTMENT</td>
        <td rowspan="2" style="width:6%;">GOV'T SERVICE<br>(Y/ N)</td>
    </tr>
    <tr class="bg-gray text-center text-xs">
        <td style="width:7%;">From</td>
        <td style="width:7%;">To</td>
    </tr>
    @for($i = 0; $i < 21; $i++)
        @php $w = $workExperiences[$i] ?? null; @endphp
        <tr>
            <td class="text-center" style="height: 25px; max-height: 25px;">{{ $w->start_at ?? '' }}</td>
            <td class="text-center">{{ $w ? ($w->end_at ?: 'Present') : '' }}</td>
            <td class="text-center" style="font-size: {{ fitFont($w->position_title ?? '') }}px;">{{ $w->position_title ?? '' }}</td>
            <td class="text-center" style="font-size: {{ fitFont($w->department_agency ?? '') }}px;">{{ $w->department_agency ?? '' }}</td>
            <td class="text-center">{{ $w->monthly_salary ?? '' }}</td>
            <td class="text-center">{{ $w->salary_grade ?? '' }}</td>
            <td class="text-center" style="font-size: {{ fitFont($w->appointment_status ?? '') }}px;">{{ $w->appointment_status ?? '' }}</td>
            <td class="text-center">{{ $w ? ($w->is_government ? 'Y' : 'N') : '' }}</td>
        </tr>
    @endfor
    <tr><td colspan="8" class="cont">(Continue on separate sheet if necessary)</td></tr>
</table>

<table class="page-footer">
    <tr>
        <td class="signature-label">SIGNATURE</td>
        <td class="signature-note">(wet signature/e-signature/digital certificate)</td>
        <td class="date-label">DATE</td>
        <td class="date-value">{{ $sig }}</td>
    </tr>
</table>

<div class="footer-reference">
    CS FORM 212 (Revised 2025), Page 2 of 4
</div>

<div class="page-break"></div>

{{-- ============================================================ PAGE 3 ============================================================ --}}
<div class="section-header">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</div>
<table style="border-top:none;">
    <tr class="bg-gray text-center text-xs">
        <td rowspan="2" style="width:46%;">29. NAME &amp; ADDRESS OF ORGANIZATION<br>(Write in full)</td>
        <td colspan="2" style="width:13%;">INCLUSIVE DATES<br>(mm/dd/yyyy)</td>
        <td rowspan="2" style="width:6%;">NUMBER OF HOURS</td>
        <td rowspan="2" style="width:32%;">POSITION / NATURE OF WORK</td>
    </tr>
    <tr class="bg-gray text-center text-xs">
        <td>From</td>
        <td>To</td>
    </tr>
    @for($i = 0; $i < 7; $i++)
        @php $vw = $voluntaryWorks[$i] ?? null; @endphp
        <tr style="height:20px;">
            <td class="text-center" style="height: 25px; max-height: 25px;">{{ $vw->organization ?? '' }}</td>
            <td class="text-center">{{ $vw->start_at ?? '' }}</td>
            <td class="text-center">{{ $vw->end_at ?? '' }}</td>
            <td class="text-center">{{ $vw->hours ?? '' }}</td>
            <td class="text-center">{{ $vw->position_nature ?? '' }}</td>
        </tr>
    @endfor
    <tr><td colspan="5" class="cont">(Continue on separate sheet if necessary)</td></tr>
</table>

<div class="section-header">VII. LEARNING AND DEVELOPMENT (L&amp;D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</div>
<table style="border-top:none;">
    <tr class="bg-gray text-center text-xs">
        <td rowspan="2" style="width:46%;">30. TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS/TRAINING PROGRAMS<br>(Write in full)</td>
        <td colspan="2" style="width:11%;">INCLUSIVE DATES<br>(mm/dd/yyyy)</td>
        <td rowspan="2" style="width:6%;">NUMBER OF HOURS</td>
        <td rowspan="2" style="width:10%;">Type of L&amp;D<br>( Managerial/ Supervisory/<br>Technical/etc)</td>
        <td rowspan="2" style="width:22%;">CONDUCTED/ SPONSORED BY<br>(Write in full)</td>
    </tr>
    <tr class="bg-gray text-center text-xs">
        <td>From</td>
        <td>To</td>
    </tr>
    @for($i = 0; $i < 12; $i++)
        @php $t = $trainings[$i] ?? null; @endphp
        <tr>
            <td class="text-center" style="height: 25px; max-height: 25px;">{{ $t->title ?? '' }}</td>
            <td class="text-center">{{ $t->start_at ?? '' }}</td>
            <td class="text-center">{{ $t->end_at ?? '' }}</td>
            <td class="text-center">{{ $t->hours ?? '' }}</td>
            <td class="text-center">{{ $t->type ?? '' }}</td>
            <td class="text-center">{{ $t->sponsored_by ?? '' }}</td>
        </tr>
    @endfor
    <tr><td colspan="6" class="cont">(Continue on separate sheet if necessary)</td></tr>
</table>

<div class="section-header">VIII. OTHER INFORMATION</div>
<table style="border-top:none; margin-bottom:0;">
    <tr class="bg-gray text-center text-xs">
        <td style="width:30%;">31. SPECIAL SKILLS and HOBBIES</td>
        <td style="width:40%;">32. NON-ACADEMIC DISTINCTIONS / RECOGNITION<br>(Write in full)</td>
        <td style="width:30%;">33. MEMBERSHIP IN ASSOCIATION/ORGANIZATION<br>(Write in full)</td>
    </tr>
    @php
        function fitFont3($text, $normal = 10)
        {
            $length = strlen($text ?? '');

            if ($length > 139) {
                return 7;
            }

            return $normal;
        }
        function fitFont2($text, $normal = 10)
        {
            $length = strlen($text ?? '');

            if ($length > 67) {
                return 7;
            }

            return $normal;
        }
    @endphp
    @for($i = 0; $i < 7; $i++)
        <tr >
            <td class="text-center" style="height: 25px; max-height: 25px; font-size: {{ fitFont2($skills[$i]->value ?? '') }}px;"">{{ $skills[$i]->value ?? '' }}</td>
            <td class="text-center" style="font-size: {{ fitFont3($distinctions[$i]->value ?? '') }}px;">{{ $distinctions[$i]->value ?? '' }}  </td>
            <td class="text-center" style="font-size: {{ fitFont2($organizations[$i]->value ?? '') }}px;">{{ $organizations[$i]->value ?? '' }}</td>
        </tr>
    @endfor
    <tr><td colspan="3" class="cont">(Continue on separate sheet if necessary)</td></tr>
</table>

<table class="page-footer">
    <tr>
        <td class="signature-label">
            SIGNATURE
        </td>

        <td class="signature-note">
            (wet signature/e-signature/digital certificate)
        </td>

        <td class="date-label">
            DATE
        </td>

        <td class="date-value">
            {{ $sig }}
        </td>
    </tr>
</table>

<div class="footer-reference">
    CS FORM 212 (Revised 2025), Page 3 of 4
</div>

<div class="page-break"></div>

{{-- ============================================================ PAGE 4 ============================================================ --}}
<table style="border:1.5px solid #000;">
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px; line-height:1.3;">
            34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the<br>
            chief of bureau or office or to the person who has immediate supervision over you in the Office,<br>
            Bureau or Department where you will be appointed,<br>
            <div style="margin-top:4px;">a. within the third degree?</div>
            <div style="margin-top:4px;">b. within the fourth degree (for Local Government Unit - Career Employees)?</div>
        </td>
        <td style="width:35%; vertical-align:top; padding:6px;" class="text-xs">
            <div>{!! $chk($declaration?->related_third_degree == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->related_third_degree !== null && $declaration->related_third_degree == 0) !!} NO</div>
            <div style="margin-top:4px;">{!! $chk($declaration?->related_fourth_degree == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->related_fourth_degree !== null && $declaration->related_fourth_degree == 0) !!} NO</div>
            <div style="margin-top:4px;">If YES, give details:<br>
                <div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->related_third_degree_details ?: $declaration?->related_fourth_degree_details) }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px;">
            35. a. Have you ever been found guilty of any administrative offense?<br><br>
            b. Have you been criminally charged before any court?
        </td>
        <td class="text-xs" style="padding:6px; vertical-align:top;">
            <div>{!! $chk($declaration?->admin_offense_found_guilty == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->admin_offense_found_guilty !== null && $declaration->admin_offense_found_guilty == 0) !!} NO</div>
            <div>If YES, give details:<br><div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->admin_offense_details) }}</div></div>
            <div style="margin-top:6px;">{!! $chk($declaration?->criminally_charged == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->criminally_charged !== null && $declaration->criminally_charged == 0) !!} NO</div>
            <div>If YES, give details:<br>Date Filed: <span style="display:inline-block; border-bottom:0.75px solid #000; width:60%;">{{ $vb($declaration?->criminal_charge_date_filed) }}</span><br>Status of Case/s: <span style="display:inline-block; border-bottom:0.75px solid #000; width:50%;">{{ $vb($declaration?->criminal_charge_case_status) }}</span></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px;">36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</td>
        <td class="text-xs" style="padding:6px;">
            <div>{!! $chk($declaration?->convicted_crime == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->convicted_crime !== null && $declaration->convicted_crime == 0) !!} NO</div>
            <div>If YES, give details:<br><div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->convicted_crime_details) }}</div></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px;">37. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</td>
        <td class="text-xs" style="padding:6px;">
            <div>{!! $chk($declaration?->separated_from_service == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->separated_from_service !== null && $declaration->separated_from_service == 0) !!} NO</div>
            <div>If YES, give details:<br><div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->separated_from_service_details) }}</div></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px;">
            38. a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?<br><br>
            b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?
        </td>
        <td class="text-xs" style="padding:6px; vertical-align:top;">
            <div>{!! $chk($declaration?->election_candidate == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->election_candidate !== null && $declaration->election_candidate == 0) !!} NO</div>
            <div>If YES, give details:<br><div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->election_candidate_details) }}</div></div>
            <div style="margin-top:6px;">{!! $chk($declaration?->resigned_to_campaign == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->resigned_to_campaign !== null && $declaration->resigned_to_campaign == 0) !!} NO</div>
            <div>If YES, give details:<br><div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->resigned_to_campaign_details) }}</div></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px;">39. Have you acquired the status of an immigrant or permanent resident of another country?</td>
        <td class="text-xs" style="padding:6px;">
            <div>{!! $chk($declaration?->immigrant_status == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->immigrant_status !== null && $declaration->immigrant_status == 0) !!} NO</div>
            <div>If YES, give details (country):<br><div style="border-bottom:0.75px solid #000; min-height:12px; width:100%;">{{ $vb($declaration?->immigrant_status_country) }}</div></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-xs" style="padding:6px;">
            40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277, as amended); and (c) Expanded Solo Parents Welfare Act (RA 11861), please answer the following items:<br>
            a. Are you a member of any indigenous group?<br>
            b. Are you a person with disability?<br>
            c. Are you a solo parent?
        </td>
        <td class="text-xs" style="padding:6px; vertical-align:top;">
            <div>{!! $chk($declaration?->indigenous_group_member == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->indigenous_group_member !== null && $declaration->indigenous_group_member == 0) !!} NO<br>If YES, specify: <span style="display:inline-block; border-bottom:0.75px solid #000; width:55%;">{{ $vb($declaration?->indigenous_group_details) }}</span></div>
            <div style="margin-top:4px;">{!! $chk($declaration?->is_pwd == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->is_pwd !== null && $declaration->is_pwd == 0) !!} NO<br>If YES, ID No: <span style="display:inline-block; border-bottom:0.75px solid #000; width:55%;">{{ $vb($declaration?->pwd_id_number) }}</span></div>
            <div style="margin-top:4px;">{!! $chk($declaration?->is_solo_parent == 1) !!} YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $chk($declaration && $declaration->is_solo_parent !== null && $declaration->is_solo_parent == 0) !!} NO<br>If YES, ID No: <span style="display:inline-block; border-bottom:0.75px solid #000; width:55%;">{{ $vb($declaration?->solo_parent_id_number) }}</span></div>
        </td>
    </tr>
</table>

<table style="margin-top:2px; border:1.5px solid #000;">
    <tr>
        <td style="width:75%; border-right:1.5px solid #000; vertical-align:top; padding:0;">
            <table class="no-border" style="border:none; margin-bottom:0;">
                <tr>
                    <td colspan="3" class="bg-gray text-xs text-bold" style="border-top:none; border-left:none; border-right:none;">41. REFERENCES <span class="text-italic">(Person not related by consanguinity or affinity to applicant /appointee)</span></td>
                </tr>
                <tr class="text-center text-xs bg-gray">
                    <td style="width:35%; border-left:none; border-top:none;">NAME</td>
                    <td style="width:40%; border-top:none;">OFFICE / RESIDENTIAL ADDRESS</td>
                    <td style="width:25%; border-right:none; border-top:none;">CONTACT NO. AND/OR EMAIL</td>
                </tr>
                @for($i = 0; $i < 3; $i++)
                    @php $rf = $references[$i] ?? null; @endphp
                    <tr style="height:22px;">
                        <td style="border-left:none;">{{ $rf->name ?? '' }}</td>
                        <td>{{ $rf->address ?? '' }}</td>
                        <td style="border-right:none;">{{ $rf->contact ?? '' }}</td>
                    </tr>
                @endfor
            </table>
            <div class="text-xs text-justify" style="padding:8px; line-height:1.35;">
                42. I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct, and complete statement pursuant to the provisions of pertinent laws, rules, and regulations of the Republic of the Philippines. I authorize the agency head/authorized representative to verify/validate the contents stated herein. I agree that any misrepresentation made in this document and its attachments shall cause the filing of administrative/criminal case/s against me.
            </div>
        </td>
        <td style="width:25%; text-align:center; vertical-align:middle; padding:5px;">
            <table style="border:0.75px solid #000; width:auto; margin:0 auto;">
                <tr>
                    <td style="width:3.5cm; height:4.5cm; text-align:center; vertical-align:middle;">
                        <div style="font-size:5pt; line-height:1.1; margin-bottom:4px;">Passport-sized unfiltered digital picture taken within the last 6 months<br>4.5 cm. X 3.5 cm</div>
                        <div style="color:#bbb; font-size:11pt; font-weight:bold;">PHOTO</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="margin-top:2px;">
    <tr>
        <td style="width:45%; border:1.5px solid #000; vertical-align:top; padding:0;">
            <table class="no-border" style="border:none;">
                <tr><td class="bg-gray text-xs text-bold" style="border-left:none; border-right:none; border-top:none;">Government Issued ID <span style="font-size:5pt; font-weight:normal;">(i.e.Passport, GSIS, SSS, PRC, Driver's License, etc.)</span><br>PLEASE INDICATE ID Number and Date of Issuance</td></tr>
                <tr><td class="text-xs" style="border-left:none; border-right:none; height:14px;">Government Issued ID: <b>{{ $vb($declaration?->government_id_type) }}</b></td></tr>
                <tr><td class="text-xs" style="border-left:none; border-right:none; height:14px;">ID/License/Passport No.: <b>{{ $vb($declaration?->government_id_number) }}</b></td></tr>
                <tr><td class="text-xs" style="border-left:none; border-right:none; border-bottom:none; height:14px;">Date/Place of Issuance: <b>{{ $vb($declaration?->government_id_issued_at) }}</b></td></tr>
            </table>
        </td>
        <td style="width:35%; border:1.5px solid #000; border-left:none; text-align:center; vertical-align:top; padding:0;">
            <table class="no-border" style="border:none;">
                <tr><td style="border:none; height:65px; text-align:center; vertical-align:middle;"><span style="color:#c00000; font-size:7.5pt;">(wet signature/e-signature/digital certificate)</span></td></tr>
                <tr><td class="text-xs text-center" style="border:none; border-top:0.75px solid #000;">Signature (Sign inside the box)</td></tr>
                <tr><td style="border:none; height:25px; border-top:0.75px solid #000; text-align:center;"><span class="text-bold">{{ $sig }}</span></td></tr>
                <tr><td class="text-xs text-center" style="border:none;">Date Accomplished</td></tr>
            </table>
        </td>
        <td style="width:20%; border:1.5px solid #000; border-left:none; text-align:center; vertical-align:top; padding:0;">
            <table class="no-border" style="border:none;">
                <tr><td style="border:none; height:80px;">&nbsp;</td></tr>
                <tr><td class="bg-gray text-xs text-center text-bold" style="border:none; border-top:0.75px solid #000;">Right Thumbmark</td></tr>
            </table>
        </td>
    </tr>
</table>

<table style="margin-top:2px; border:1.5px solid #000;">
    <tr>
        <td class="text-xs text-center" style="padding:10px;">
            SUBSCRIBED AND SWORN to before me this <span style="display:inline-block; border-bottom:0.75px solid #000; width:180px;">&nbsp;</span>, affiant exhibiting his/her validly issued government ID as indicated above.
        </td>
    </tr>
</table>
<table style="margin:6px auto 0 auto; width:350px; border:1.5px solid #000;">
    <tr><td style="height:40px; text-align:center; vertical-align:middle; border:none;"><span style="color:#c00000; font-size:7.5pt;">(wet signature/e-signature/digital certificate except for notary public)</span></td></tr>
    <tr><td class="bg-gray text-bold text-center" style="border:none; border-top:1.5px solid #000; padding:4px;">Person Administering Oath</td></tr>
</table>

<div class="foot">CS FORM 212 (Revised 2026), Page 4 of 4</div>

</body>
</html>
