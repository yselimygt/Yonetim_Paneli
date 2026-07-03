<?php
$json = file_get_contents('scratch_parsed_universities.json');
if (!is_dir('backend/public/assets/admin/js')) {
    mkdir('backend/public/assets/admin/js', 0777, true);
}
file_put_contents('backend/public/assets/admin/js/universities_data.js', "const KON_UNIS = " . $json . ";\n");
echo "JS file created.\n";
