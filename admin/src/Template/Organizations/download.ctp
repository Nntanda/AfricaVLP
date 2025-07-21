<?php
//ini_set("memory_limit",-1);
//var_dump($organizations->toArray());
$line = $organizations->toArray();
// $this->CSV->addRow(array_keys($line));
// foreach ($organizations as $organization) {
//     $line = $organization['Organization'];
//     $this->CSV->addRow($line);
// }
$filename = 'organizations-' . date('Y-m-d H:i:s') . '.csv';
// echo  $this->CSV->render($filename);



$headers_array = [
    'id',
'organization_type_id',
'name',
'about',
'country_id',
'city_id',
'logo',
'institution_type_id',
'government_affliliation',
'category_id',
'date_of_establishment',
'address',
'lat',
'lng',
'email',
'phone_number',
'website',
'facebook_url',
'instagram_url',
'twitter_url',
'user_id',
'status',
'is_verified',
'created',
'modified',
'pan_africanism',
'education_skills',
'health_wellbeing',
'no_poverty',
'agriculture_rural',
'democratic_values',
'environmental_sustainability',
'infrastructure_development',
'peace_security',
'culture',
'gender_inequality',
'youth_empowerment',
'reduced_inequality',
'sustainable_city',
'responsible_consumption',
'pan_africanism_pan',
'education_skills_pan',
'citizen_health_pan',
'poverty_pan',
'pan_africanism_edu',
'education_skills_edu',
'citizen_health_edu',
'poverty_edu',
'pan_africanism_health',
'education_skills_health',
'citizen_health_health',
'poverty_health',
'pan_africanism_nopov',
'education_skills_nopov',
'citizen_health_nopov',
'poverty_nopov',
'pan_africanism_agric',
'education_skills_agric',
'citizen_health_agric
poverty_agric',
'pan_africanism_demo',
'education_skills_demo',
'citizen_health_demo',
'poverty_demo',
'pan_africanism_enviro',
'education_skills_enviro',
'citizen_health_enviro',
'poverty_enviro',
'pan_africanism_infra',
'education_skills_infra',
'citizen_health_infra',
'poverty_infra',
'pan_africanism_peace',
'education_skills_peace',
'citizen_health_peace',
'poverty_peace',
'pan_africanism_culture',
'education_skills_culture',
'citizen_health_culture',
'poverty_culture',
'pan_africanism_gender',
'education_skills_gender',
'citizen_health_gender',
'poverty_gender',
'pan_africanism_youth',
'education_skills_youth',
'citizen_health_youth',
'poverty_youth',
'pan_africanism_reduced',
'education_skills_reduced',
'citizen_health_reduced',
'poverty_reduced',
'pan_africanism_sustainable',
'education_skills_sustainable',
'citizen_health_sustainable',
'poverty_sustainable',
'pan_africanism_responsible',
'education_skills_responsible',
'citizen_health_responsible',
'poverty_responsible',
'volunteer_exchange_region',
'volunteer_exchange_intern',
'pan_africanism_resources',
'pan_africanism_organiz_pol',
'pan_africanism_organiz_annu',
'pan_africanism_organiz_pol_file',
'pan_africanism_organiz_annu_file',
'additional_file',
'pan_africanism_country_file'
];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$filename);

$output = fopen('php://output', 'w');
ob_end_clean();

$liner = json_decode(json_encode($line), True);
fputcsv($output, array_keys($liner[0]));


foreach(array_values($line) as $line_new){
    $row = json_decode(json_encode($line_new), True);
    fputcsv($output, $row);
}

exit;

