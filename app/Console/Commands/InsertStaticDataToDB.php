<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertStaticDataToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insert-static';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Title, Country, Industry data to DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('Title')->truncate();
        DB::table('Country')->truncate();
        DB::table('Industry')->truncate();
        DB::table('Academy')->truncate();

        DB::beginTransaction();
        // iterate each title in scholar_title
        foreach ($this->scholar_title as $title) {
            // insert title to Title table
            DB::table('Title')->insert([
                'name' => $title,
                'belongs_to' => 'scholar'
            ]);
        }

        // iterate each title in employer_title
        foreach ($this->employer_title as $title) {
            // insert title to Title table
            DB::table('Title')->insert([
                'name' => $title,
                'belongs_to' => 'employer'
            ]);
        }

        // iterate each industry
        foreach ($this->industry as $industry) {
            // insert industry to Industry table
            DB::table('Industry')->insert([
                'name' => $industry
            ]);
        }

        // iterate each country
        foreach ($this->country as $country) {
            // insert country to Country table
            DB::table('Country')->insert([
                'name' => $country
            ]);
        }

        foreach ($this->academy_list as $academy) {
            // insert country to Country table
            $no = substr($academy, 0, 2);
        
            DB::table('Academy')->insert([
                'Academy_Name' => $academy,
                'Academy_No' => $no
            ]);
        }
        
        DB::commit();
    }

    private $academy_list = [
        '01人文社會學院',
        '02人文與社會科學院',
        '03工學院',
        '04牙醫學院',
        '05生命科學院',
        '06生物科技學院',
        '07生物醫學暨工程學院',
        '08光電學院',
        '09客家文化學院',
        '10科技法律學院',
        '12產學創新學院',
        '13理學院',
        '14國際半導體學院',
        '15智慧科學暨綠能學院',
        '16電機學院',
        '17資訊學院',
        '18管理學院',
        '19醫學院',
        '20藥物科學院',
        '21護理學院',
    ];
    private $scholar_title = [
        'Distinguished Professor',
        'Professor',
        'Associate Professor',
        'Assistant Professor',
        'Research Fellow',
        'Postdoctoral Researcher',
        'Lecturer',
        '其他'
    ];
    
    private $employer_title = [
        'Mr.',
        'Mrs.',
        'Miss',
        'Ms.'
    ];

    private $industry = [
        '水泥工業類',
        '食品工業類',
        '塑膠工業類',
        '紡織纖維類',
        '電機機械類',
        '電器電纜類',
        '玻璃陶瓷類',
        '造紙工業類',
        '鋼鐵工業類',
        '橡膠工業類',
        '汽車工業類',
        '建材營造類',
        '航運業類',
        '觀光事業類',
        '金融保險類',
        '貿易百貨類',
        '化學工業類',
        '生技醫療類',
        '油電燃氣類',
        '半導體業類',
        '電腦及週邊設備業類',
        '光電業類',
        '通信網路業類',
        '電子零組件業類',
        '電子通路業類',
        '資訊服務業類',
        '其他電子業類',
        '化學生技醫療類',
        '綜合',
        '學校及公家機關',
        '其他類'
    ];

    private $country = [
        'Afghanistan',
        'Albania',
        'Algeria',
        'American Samoa',
        'Andorra',
        'Angola',
        'Antigua and Barbuda',
        'Argentina',
        'Armenia',
        'Aruba',
        'Australia',
        'Austria',
        'Azerbaijan',
        'Bahamas',
        'Bahrain',
        'Bangladesh',
        'Barbados',
        'Belarus',
        'Belgium',
        'Belize',
        'Benin',
        'Bermuda',
        'Bhutan',
        'Bolivia',
        'Bosnia and Herzegovina',
        'Botswana',
        'Brazil',
        'British Virgin Islands',
        'Brunei',
        'Bulgaria',
        'Burkina Faso',
        'Burundi',
        'Cabo Verde',
        'Cambodia',
        'Cameroon',
        'Canada',
        'Cayman Islands',
        'Central African Republic',
        'Chad',
        'Chile',
        'China (Mainland)',
        'Colombia',
        'Comoros',
        'Costa Rica',
        'Cote d\'Ivoire',
        'Crimea',
        'Croatia',
        'Cuba',
        'Curacao',
        'Cyprus',
        'Czech Republic',
        'Democratic Republic of the Congo',
        'Denmark',
        'Djibouti',
        'Dominica',
        'Dominican Republic',
        'Ecuador',
        'Egypt',
        'El Salvador',
        'Equatorial Guinea',
        'Eritrea',
        'Estonia',
        'Ethiopia',
        'Faroe Islands',
        'Fiji',
        'Finland',
        'France',
        'French Polynesia',
        'Gabon',
        'Gambia',
        'Georgia',
        'Germany',
        'Ghana',
        'Gibraltar',
        'Greece',
        'Greenland',
        'Grenada',
        'Guam',
        'Guatemala',
        'Guinea',
        'Guinea-Bissau',
        'Guyana',
        'Haiti',
        'Honduras',
        'Hong Kong SAR',
        'Hungary',
        'Iceland',
        'India',
        'Indonesia',
        'Iran, Islamic Republic of',
        'Iraq',
        'Ireland',
        'Isle of Man',
        'Israel',
        'Italy',
        'Jamaica',
        'Japan',
        'Jordan',
        'Kazakhstan',
        'Kenya',
        'Kiribati',
        'Kosovo',
        'Kuwait',
        'Kyrgyzstan',
        'Laos',
        'Latvia',
        'Lebanon',
        'Lesotho',
        'Liberia',
        'Libya',
        'Liechtenstein',
        'Lithuania',
        'Luxembourg',
        'Macau SAR',
        'Madagascar',
        'Malawi',
        'Malaysia',
        'Maldives',
        'Mali',
        'Malta',
        'Marshall Islands',
        'Mauritania',
        'Mauritius',
        'Mexico',
        'Micronesia',
        'Moldova',
        'Monaco',
        'Mongolia',
        'Montenegro',
        'Morocco',
        'Mozambique',
        'Myanmar',
        'Namibia',
        'Nauru',
        'Nepal',
        'Netherlands',
        'Netherlands Antilles',
        'New Caledonia',
        'New Zealand',
        'Nicaragua',
        'Niger',
        'Nigeria',
        'North Korea',
        'North Macedonia',
        'Northern Cyprus',
        'Northern Mariana Islands',
        'Norway',
        'Oman',
        'Pakistan',
        'Palau',
        'Palestinian Territory, Occupied',
        'Panama',
        'Papua New Guinea',
        'Paraguay',
        'Peru',
        'Philippines',
        'Poland',
        'Portugal',
        'Puerto Rico',
        'Qatar',
        'Republic of the Congo',
        'Romania',
        'Russia',
        'Rwanda',
        'Saint Kitts and Nevis',
        'Saint Lucia',
        'Saint Martin',
        'Saint Vincent and the Grenadines',
        'Samoa',
        'San Marino',
        'Sao Tome and Principe',
        'Saudi Arabia',
        'Senegal',
        'Serbia',
        'Seychelles',
        'Sierra Leone',
        'Singapore',
        'Sint Maarten',
        'Slovakia',
        'Slovenia',
        'Solomon Islands',
        'Somalia',
        'South Africa',
        'South Korea',
        'South Sudan',
        'Spain',
        'Sri Lanka',
        'Sudan',
        'Suriname',
        'Swaziland',
        'Sweden',
        'Switzerland',
        'Syrian Arab Republic',
        'Taiwan',
        'Tajikistan',
        'Tanzania',
        'Thailand',
        'Timor-Leste',
        'Togo',
        'Tonga',
        'Trinidad and Tobago',
        'Tunisia',
        'Turkey',
        'Turkmenistan',
        'Turks and Caicos Islands',
        'Tuvalu',
        'Uganda',
        'Ukraine',
        'United Arab Emirates',
        'United Kingdom',
        'United States',
        'Uruguay',
        'Uzbekistan',
        'Vanuatu',
        'Vatican City',
        'Venezuela',
        'Vietnam',
        'Virgin Islands (US)',
        'Yemen',
        'Zambia',
        'Zimbabwe'
    ];
}
