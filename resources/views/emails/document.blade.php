<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f4f7;font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f7;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#3b82f6;padding:28px 32px;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:bold;">{{ $studioName }}</h1>
                            <p style="margin:4px 0 0;color:rgba(255,255,255,0.85);font-size:13px;">Photography &amp; Films</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;color:#1a1a1a;font-size:16px;">Dear {{ $clientName }},</p>

                            @foreach ($lines as $line)
                            <p style="margin:0 0 14px;color:#444;font-size:14px;line-height:1.6;">{{ $line }}</p>
                            @endforeach

                            <div style="margin:24px 0;padding:16px 20px;background:#f0f6ff;border-left:4px solid #3b82f6;border-radius:6px;">
                                <p style="margin:0;color:#1a1a1a;font-size:14px;">
                                    📎 Your {{ strtolower($docType) }} <strong>{{ $docNumber }}</strong> is attached as a PDF.
                                </p>
                            </div>

                            <p style="margin:24px 0 0;color:#444;font-size:14px;line-height:1.6;">
                                Thank you for choosing {{ $studioName }}.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 32px;background:#fafafa;border-top:1px solid #eee;">
                            <p style="margin:0;color:#999;font-size:12px;text-align:center;">
                                © {{ date('Y') }} {{ $studioName }}. This is an automated message.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
