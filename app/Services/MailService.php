<?php

namespace App\Services;

use App\Models\{Employee, Department};

class MailService
{
    /**
     * Envía un correo electrónico en formato HTML.
     *
     * @param string $to La dirección de correo electrónico del destinatario..
     * @param string $subject El asunto del correo electrónico.
     * @param string $message El contenido HTML del correo electrónico.
     * @param string $from La dirección de correo electrónico del remitente.
     * @param string $replyTo La dirección de correo electrónico para las respuestas.
     * @return bool Devuelve `true` si el correo se envía correctamente, `false` en caso contrario.
     */
    public function send($to, $subject, $message, $from = 'Noreply@ejemplo.com', $replyTo = 'Noreply@ejemplo.com')
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . '' . "\r\n" .
                    'Reply-To: ' . $replyTo . '' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function sendResponsible($request, $subject, $message)
    {
        if(get_array_value($request, 'COMRES') == '*') {
            $department = Department::where([
                 'COMP' => get_array_value($request, 'COMP'),
                 'SUC' => get_array_value($request, 'SUC'),
                 'COD' => get_array_value($request, 'DTO'),
             ])->first();

             if($department->ENCAR != 0) {
                 $responsible = Employee::where(['COD' => $department->ENCAR])->first();
                 $this->send(to: $responsible?->EMAIL, subject: $subject, message: $message);
             }
        }
    }

    public function sendEmployee($request, $subject, $message)
    {
        if(get_array_value($request, 'COMTRA') == '*') {
            $email = get_array_value($request, 'employee.EMAIL');
            $this->send(to: $email, subject: $subject, message: $message);
        }
    }
}
