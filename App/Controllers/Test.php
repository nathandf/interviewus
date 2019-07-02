<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sample.csv"');

        $user_CSV[0] = array('first_name', 'last_name', 'age');

        // very simple to increment with i++ if looping through a database result
        $user_CSV[1] = array('Quentin', 'Del Viento', 34);
        $user_CSV[2] = array('Antoine', 'Del Torro', 55);
        $user_CSV[3] = array('Arthur', 'Vincente', 15);

        $fp = fopen('php://output', 'wb');
        foreach ($user_CSV as $line) {
            // though CSV stands for "comma separated value"
            // in many countries (including France) separator is ";"
            fputcsv($fp, $line, ',');
        }
        fclose($fp);
    }
}
