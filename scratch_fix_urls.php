<?php
$files = [
    'backend/views/superadmin/site_team/index.php',
    'backend/models/SiteTeamProfile.php',
    'frontend/src/data/innomisData.jsx',
    'frontend/src/components/Team.jsx',
    'frontend/src/components/Alumni.jsx'
];
foreach($files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('/innomis/assets/uni/', '/assets/uni/', $content);
        file_put_contents($file, $content);
    }
}
echo 'Fixed logo URLs.';
