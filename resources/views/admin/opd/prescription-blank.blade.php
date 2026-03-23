<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Prescription Print - {{ $opd->opd_number }}</title>
    <style>
        /* 1. Reset A4 Paper */
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
            font-family: Arial, sans-serif;
            color: #000;
            background: transparent;
        }

        /* 2. DOCTOR INFO AREA (Inside your Red Box) */
        .doctor-box {
            position: absolute;
            top: 10mm;
            /* Distance from top edge */
            left: 6mm;
            /* Distance from left edge */
            width: 70mm;
            /* Width of the blue sidebar area */
            line-height: 1.3;
        }

        .dr-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2mm;
            color: #1a237e;
            /* Dark blue to look professional */
        }

        .dr-qualification {
            font-size: 9pt;
            margin-bottom: 1mm;
        }

        .dr-reg {
            font-size: 9pt;
            font-style: italic;
        }

        /* 3. PATIENT INFO AREA (Inside the Purple Bar) */
        .patient-row {
            position: absolute;
            top: 48mm;
            /* ADJUST: Move down/up to center in the purple bar */
            left: 0;
            width: 210mm;
            font-size: 10.5pt;
            font-weight: bold;
        }

        .val-name {
            position: absolute;
            left: 28mm;
        }

        /* After "Pt. Name :" */
        .val-age {
            position: absolute;
            left: 119mm;
        }

        /* After "Age :" */
        .val-date {
            position: absolute;
            left: 137mm;
        }

        /* After "Date :" */
        .val-id {
            position: absolute;
            left: 173mm;
        }

        /* After "ID :" */

        /* UI Elements */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <!-- 1. Doctor Info (Top Left Sidebar) -->
    <div class="doctor-box">
        <div class="dr-name">Dr. {{ $opd->doctor->name }}</div>
        <div class="dr-qualification">
            {!! $opd->doctor->qualification !!}
        </div>
    </div>

    <!-- 2. Patient Info (Purple Header Bar) -->
    <div class="patient-row">
        <div class="val-name">{{ $opd->patient->user->name }}</div>
        <div class="val-age">{{ $opd->patient->age }}</div>
        <div class="val-date">{{ date('d-m-Y') }}</div>
        <div class="val-id">{{ $opd->opd_number }}</div>
    </div>

    <!-- Floating Help Button (Screen Only) -->
    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px; background: white; padding: 15px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            PRINT NOW
        </button>
        <div style="font-size: 10px; margin-top: 10px; color: #666;">
            <strong>Calibration Tip:</strong><br>
            • Use <b>Scale: 100%</b><br>
            • Set <b>Margins: None</b>
        </div>
    </div>

</body>

</html>