@props([
    'pet',
    // Cada responsiva usa un subconjunto distinto de estos datos opcionales
    // (ver reception/pdf.blade.php -contacto de emergencia-, budget/pdf.blade.php
    // -correo y descripción física-); por defecto no se muestran, para no
    // inventar información que ese documento en particular nunca mostró.
    'showEmergencyContact' => false,
    'showEmail' => false,
    'showPhysicDescription' => false,
])

@php
    // Mismo helper de íconos que ya duplicaban los 4 documentos que usaban
    // este bloque — aquí solo queda el ícono de las etiquetas "Propietario"/
    // "Mascota" (ver reception/pdf.blade.php, diseño de referencia: los
    // datos de cada línea ya no llevan ícono propio).
    $icons = [
        'user' =>
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM3.5 14.5a1.2 1.2 0 0 0 .4 1.4A10 10 0 0 0 10 18c2.3 0 4.4-.8 6.1-2.1.4-.3.6-.9.4-1.4a7 7 0 0 0-13 0z"/></svg>',
        'paw' =>
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><circle cx="6" cy="6" r="2"/><circle cx="14" cy="6" r="2"/><circle cx="3.3" cy="11" r="1.8"/><circle cx="16.7" cy="11" r="1.8"/><ellipse cx="10" cy="14.5" rx="5" ry="4"/></svg>',
    ];

    $icon = function ($name, $size = 15, $color = '#0455A0') use ($icons) {
        $svg = str_replace('{color}', $color, $icons[$name]);
        return '<img src="data:image/svg+xml;base64,' .
            base64_encode($svg) .
            '" width="' .
            $size .
            '" height="' .
            $size .
            '" style="vertical-align:middle;margin-right:4px;">';
    };

    $family = $pet->family;

    // Algunos documentos (ej. FormatController::responsivaEg() para el
    // formulario genérico de Formatos) pueden llegar con $pet->birthday
    // vacío; sin este guard, Carbon::parse(null) truena.
    $years = null;
    $months = null;
    if ($pet->birthday) {
        $birthday = \Carbon\Carbon::parse($pet->birthday);
        $years = $birthday->diffInYears(now());
        $months = $birthday->copy()->addYears($years)->diffInMonths(now());
    }
@endphp

<table class="cards-table">
    <tr>
        <td class="card-cell" style="padding-right: 8px;">
            <div class="card-box">
                <div class="card-label">{!! $icon('user') !!}Propietario</div>
                <p class="owner-name">{{ $family->name ?? '' }}</p>
                <p class="owner-line">Tel. {{ $family->phone ?? '' }}</p>
                <p class="owner-line">Domicilio: {{ $family->address ?? '' }}</p>
                @if ($showEmail)
                    <p class="owner-line">{{ $family->email ?? '' }}</p>
                @endif
               
                    <p class="owner-line">Emergencia: {{ $family->contact_name ?? '' }} &middot;
                        {{ $family->contact_number ?? '' }}</p>
              
            </div>
        </td>
        <td class="card-cell" style="padding-left: 8px;">
            <div class="card-box">
                <div class="card-label">{!! $icon('paw') !!}Mascota</div>
                <p class="pet-name">{{ $pet->name }}</p>
                <p class="owner-line">{{ $pet->raza ?: $pet->specie }} &nbsp;&nbsp; {{ $pet->genre->name ?? '—' }}</p>
                <p class="owner-line">{{ $pet->weight ? $pet->weight . ' kg' : '—' }}</p>
                @if ($years !== null)
                    <p class="owner-line">{{ $years }} años, {{ $months }} meses</p>
                @endif
                @if ($showPhysicDescription && $pet->physic_descrip)
                    <p class="owner-line">{{ $pet->physic_descrip }}</p>
                @endif
            </div>
        </td>
    </tr>
</table>
