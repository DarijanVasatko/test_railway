{{-- resources/views/emails/order-receipt.blade.php --}}
<!doctype html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>Račun za vašu narudžbu </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
    <tr>
        <td align="center" style="padding:24px 12px;">
            <!-- Card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; border-collapse:collapse; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(15,23,42,0.12);">
                <!-- Header -->
                <tr>
                    <td style="background:linear-gradient(135deg,#111827,#1f2937); padding:20px 24px; color:#f9fafb;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <td style="font-size:20px; font-weight:700;">
                                    TechShop
                                </td>
                                
                            </tr>
                        </table>
                    </td>
                </tr>

                
                <tr>
                    <td style="padding:24px;">
                        <!-- Uvod -->
                        <p style="margin:0 0 12px 0; font-size:16px; font-weight:600; color:#111827;">
                            Hvala na kupnji, {{ $order->user->ime }}!
                        </p>
                        <p style="margin:0 0 20px 0; font-size:14px; color:#4b5563; line-height:1.6;">
                            U nastavku je sažetak vaše narudžbe. Ovaj račun je automatski generiran i služi kao potvrda kupnje.
                        </p>

                        <!-- Podaci o narudžbi -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px;">
                            <tr>
                                <td style="width:50%; vertical-align:top; padding-right:8px;">
                                    <div style="border-radius:10px; border:1px solid #e5e7eb; padding:12px 14px;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; margin-bottom:4px;">
                                            Podaci kupca
                                        </div>
                                        <div style="font-size:14px; color:#111827; line-height:1.6;">
                                            <strong>{{ $order->user->ime }} {{ $order->user->prezime }}</strong><br>
                                            
                                            {{ $order->Adresa_dostave ?? $order->user->adresa }}<br>
                                            {{ $order->user->postanski_broj ?? '' }} {{ $order->user->grad ?? '' }}<br>
                                            @if(!empty($order->user->telefon))
                                                Tel: {{ $order->user->telefon }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="width:50%; vertical-align:top; padding-left:8px;">
                                    <div style="border-radius:10px; border:1px solid #e5e7eb; padding:12px 14px;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; margin-bottom:4px;">
                                            Podaci o računu
                                        </div>
                                        <div style="font-size:14px; color:#111827; line-height:1.6;">
                                            <strong>TechShop d.o.o.</strong><br>
                                            Ivana Gundulića 14, 43500 Daruvar<br>
                                            OIB: 12345678901<br><br>

                                            Datum računa: {{ $order->created_at->format('d.m.Y') }}<br>
                                            Način plaćanja: {{ $order->nacinPlacanja->naziv ?? 'Kartica' }}<br>
                                            Status: {{ $order->Status }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- stavke narudžbe -->
                        @php
                            $subtotal = 0;
                        @endphp

                        <div style="border-radius:10px; border:1px solid #e5e7eb; overflow:hidden;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <thead>
                                <tr>
                                    <th align="left" style="padding:10px 12px; font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; background-color:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                        Proizvod
                                    </th>
                                    <th align="center" style="padding:10px 8px; font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; background-color:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                        Količina
                                    </th>
                                    <th align="right" style="padding:10px 8px; font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; background-color:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                        Cijena
                                    </th>
                                    <th align="right" style="padding:10px 12px; font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; background-color:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                        Iznos
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->detalji as $stavka)
                                    @php
                                        $proizvod = $stavka->proizvod;
                                        $lineTotal = $stavka->kolicina * $stavka->cijena;
                                        $subtotal += $lineTotal;
                                    @endphp
                                    <tr style="background-color: {{ $loop->odd ? '#ffffff' : '#f9fafb' }};">
                                        <td style="padding:10px 12px; font-size:13px; color:#111827; border-bottom:1px solid #e5e7eb;">
                                            <div style="font-weight:600;">
                                                {{ $proizvod->naziv }}
                                            </div>
                                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">
                                                Šifra: {{ $proizvod->sifra ?? '-' }}
                                            </div>
                                        </td>
                                        <td align="center" style="padding:10px 8px; font-size:13px; color:#111827; border-bottom:1px solid #e5e7eb;">
                                            {{ number_format($stavka->kolicina, 0) }}
                                        </td>
                                        <td align="right" style="padding:10px 8px; font-size:13px; color:#111827; border-bottom:1px solid #e5e7eb;">
                                            {{ number_format($stavka->cijena, 2) }} €
                                        </td>
                                        <td align="right" style="padding:10px 12px; font-size:13px; color:#111827; border-bottom:1px solid #e5e7eb;">
                                            {{ number_format($lineTotal, 2) }} €
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- ukupno -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-top:16px;">
                            <tr>
                                <td style="width:60%;"></td>
                                <td style="width:40%;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                        <tr>
                                            <td align="left" style="padding:4px 0; font-size:13px; color:#6b7280;">
                                                Međuiznos:
                                            </td>
                                            <td align="right" style="padding:4px 0; font-size:13px; color:#111827;">
                                                {{ number_format($subtotal, 2) }} €
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="padding:4px 0; font-size:13px; color:#6b7280;">
                                                Dostava:
                                            </td>
                                            <td align="right" style="padding:4px 0; font-size:13px; color:#111827;">
                                                0,00 €
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="padding:8px 0 0 0; font-size:14px; font-weight:600; color:#111827; border-top:1px solid #e5e7eb;">
                                                Ukupno za platiti:
                                            </td>
                                            <td align="right" style="padding:8px 0 0 0; font-size:16px; font-weight:700; color:#111827; border-top:1px solid #e5e7eb;">
                                                {{ number_format($order->Ukupni_iznos, 2) }} €
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- footer -->
                        <p style="margin:20px 0 6px 0; font-size:13px; color:#6b7280; line-height:1.6;">
                            Račun je kompjuterski izdan i vrijedi bez pečata i potpisa.
                        </p>
                        <p style="margin:0 0 4px 0; font-size:12px; color:#9ca3af;">
                            Ako imate bilo kakvih pitanja u vezi narudžbe, slobodno nam se javite na
                            <a href="mailto:podrska@techshop.hr" style="color:#3b82f6; text-decoration:none;">podrska@techshop.hr</a>.
                        </p>
                        <p style="margin:0; font-size:11px; color:#9ca3af;">
                            &copy; {{ date('Y') }} TechShop d.o.o. Sva prava pridržana.
                        </p>
                    </td>
                </tr>
            </table>
            
        </td>
    </tr>
</table>

</body>
</html>
