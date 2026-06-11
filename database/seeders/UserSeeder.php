<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Enums\StartupStage;
use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\IncubatorProfile;
use App\Models\StartupProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sania.africa'],
            [
                'name' => 'SANIA Admin',
                'password' => Hash::make('password'),
                'role' => Role::Admin,
                'country' => 'RW',
                'is_active' => true,
            ]
        );
        ActivityLog::record('user.registered', $admin, ['role' => 'admin'], $admin->id);

        // Incubators
        $incubators = [
            [
                'user' => ['name' => 'Hanga Hub', 'email' => 'incubator@hanga.rw', 'country' => 'RW'],
                'profile' => [
                    'organization_name' => 'Hanga Hub',
                    'description' => 'Hanga Hub is Rwanda\'s premier tech incubator, nurturing the next generation of African entrepreneurs through mentorship, workspace, and funding access.',
                    'country' => 'RW',
                    'city' => 'Kigali',
                    'website' => 'https://hangahub.rw',
                    'focus_sectors' => ['fintech', 'agritech', 'healthtech', 'edtech'],
                    'supported_stages' => ['idea', 'mvp', 'early_traction'],
                    'capacity' => 30,
                    'is_verified' => true,
                    'founded_year' => 2012,
                ],
            ],
            [
                'user' => ['name' => 'CcHUB Nigeria', 'email' => 'hello@cchub.africa', 'country' => 'NG'],
                'profile' => [
                    'organization_name' => 'CcHUB',
                    'description' => 'Co-Creation Hub is Nigeria\'s leading innovation centre, building a community of innovators solving Africa\'s challenges with technology and creativity.',
                    'country' => 'NG',
                    'city' => 'Lagos',
                    'website' => 'https://cchub.africa',
                    'focus_sectors' => ['fintech', 'govtech', 'media', 'healthtech'],
                    'supported_stages' => ['mvp', 'early_traction', 'growth'],
                    'capacity' => 50,
                    'is_verified' => true,
                    'founded_year' => 2011,
                ],
            ],
            [
                'user' => ['name' => 'iHub Nairobi', 'email' => 'info@ihub.co.ke', 'country' => 'KE'],
                'profile' => [
                    'organization_name' => 'iHub Nairobi',
                    'description' => 'iHub is East Africa\'s top tech hub, connecting entrepreneurs, developers, investors and companies to build successful tech startups.',
                    'country' => 'KE',
                    'city' => 'Nairobi',
                    'website' => 'https://ihub.co.ke',
                    'focus_sectors' => ['fintech', 'agritech', 'logistics', 'ecommerce'],
                    'supported_stages' => ['idea', 'mvp', 'early_traction', 'growth'],
                    'capacity' => 40,
                    'is_verified' => true,
                    'founded_year' => 2010,
                ],
            ],
            [
                'user' => ['name' => 'MEST Africa', 'email' => 'info@mestghana.org', 'country' => 'GH'],
                'profile' => [
                    'organization_name' => 'MEST Africa',
                    'description' => 'MEST Africa is a pan-African incubator and seed fund focused on creating and investing in tech companies that solve African problems at scale.',
                    'country' => 'GH',
                    'city' => 'Accra',
                    'website' => 'https://mests.org',
                    'focus_sectors' => ['fintech', 'ecommerce', 'logistics', 'media'],
                    'supported_stages' => ['mvp', 'early_traction', 'growth'],
                    'capacity' => 25,
                    'is_verified' => true,
                    'founded_year' => 2008,
                ],
            ],
            [
                'user' => ['name' => 'Flat6Labs Cairo', 'email' => 'apply@flat6labs.com', 'country' => 'EG'],
                'profile' => [
                    'organization_name' => 'Flat6Labs',
                    'description' => 'Flat6Labs is MENA\'s leading startup accelerator with a presence across North Africa, empowering bold entrepreneurs to build game-changing companies.',
                    'country' => 'EG',
                    'city' => 'Cairo',
                    'website' => 'https://flat6labs.com',
                    'focus_sectors' => ['fintech', 'healthtech', 'edtech', 'legaltech'],
                    'supported_stages' => ['idea', 'mvp', 'early_traction'],
                    'capacity' => 20,
                    'is_verified' => true,
                    'founded_year' => 2011,
                ],
            ],
            [
                'user' => ['name' => 'Jokkolabs Dakar', 'email' => 'contact@jokkolabs.net', 'country' => 'SN'],
                'profile' => [
                    'organization_name' => 'Jokkolabs Dakar',
                    'description' => 'Jokkolabs is a pan-African coworking and incubation network fostering digital innovation across West Africa.',
                    'country' => 'SN',
                    'city' => 'Dakar',
                    'website' => 'https://jokkolabs.net',
                    'focus_sectors' => ['fintech', 'agritech', 'climate', 'govtech'],
                    'supported_stages' => ['idea', 'mvp'],
                    'capacity' => 15,
                    'is_verified' => false,
                    'founded_year' => 2010,
                ],
            ],
            [
                'user' => ['name' => 'ActivSpaces Cameroon', 'email' => 'hello@activspaces.com', 'country' => 'CM'],
                'profile' => [
                    'organization_name' => 'ActivSpaces',
                    'description' => 'ActivSpaces is Central Africa\'s leading tech hub and innovation center, empowering entrepreneurs to build scalable tech businesses.',
                    'country' => 'CM',
                    'city' => 'Buea',
                    'website' => 'https://activspaces.com',
                    'focus_sectors' => ['fintech', 'agritech', 'healthtech'],
                    'supported_stages' => ['idea', 'mvp', 'early_traction'],
                    'capacity' => 20,
                    'is_verified' => false,
                    'founded_year' => 2011,
                ],
            ],
            [
                'user' => ['name' => 'Impact Hub Johannesburg', 'email' => 'info@impacthub.co.za', 'country' => 'ZA'],
                'profile' => [
                    'organization_name' => 'Impact Hub Johannesburg',
                    'description' => 'Impact Hub Joburg is a community and coworking space for purpose-driven entrepreneurs and innovators building a better Southern Africa.',
                    'country' => 'ZA',
                    'city' => 'Johannesburg',
                    'website' => 'https://johannesburg.impacthub.net',
                    'focus_sectors' => ['climate', 'cleantech', 'proptech', 'ecommerce'],
                    'supported_stages' => ['mvp', 'early_traction', 'growth', 'scale'],
                    'capacity' => 35,
                    'is_verified' => true,
                    'founded_year' => 2013,
                ],
            ],
        ];

        $incubatorUsers = [];
        foreach ($incubators as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']],
                [
                    'name' => $data['user']['name'],
                    'password' => Hash::make('password'),
                    'role' => Role::Incubator,
                    'country' => $data['user']['country'],
                    'is_active' => true,
                ]
            );
            $profileData = $data['profile'];
            $profileData['user_id'] = $user->id;
            $profileData['slug'] = Str::slug($profileData['organization_name']);
            IncubatorProfile::firstOrCreate(['user_id' => $user->id], $profileData);
            ActivityLog::record('user.registered', $user, ['role' => 'incubator'], $user->id);
            $incubatorUsers[] = $user;
        }

        // Startups
        $startupData = [
            ['name' => 'Kasha Rwanda', 'email' => 'hello@kasha.co', 'country' => 'RW', 'profile' => ['company_name' => 'Kasha', 'tagline' => 'E-commerce for women\'s health', 'description' => 'Kasha is a digital-first commerce and distribution platform focused on health, hygiene, and personal care products for women across East Africa.', 'sector' => 'healthtech', 'stage' => 'growth', 'city' => 'Kigali', 'team_size' => 45]],
            ['name' => 'Paystack Africa', 'email' => 'info@paystack.demo', 'country' => 'NG', 'profile' => ['company_name' => 'BridgePay', 'tagline' => 'Seamless payments across Africa', 'description' => 'BridgePay enables businesses to accept digital payments from anyone in Africa using a single API integration.', 'sector' => 'fintech', 'stage' => 'scale', 'city' => 'Lagos', 'team_size' => 120]],
            ['name' => 'AgroHub Kenya', 'email' => 'founder@agrohub.co.ke', 'country' => 'KE', 'profile' => ['company_name' => 'AgroHub', 'tagline' => 'Connecting farmers to markets', 'description' => 'AgroHub is a B2B marketplace that connects smallholder farmers directly to buyers, removing middlemen and increasing farmer incomes by up to 40%.', 'sector' => 'agritech', 'stage' => 'early_traction', 'city' => 'Nairobi', 'team_size' => 18]],
            ['name' => 'EduReach Ghana', 'email' => 'team@edureachghana.com', 'country' => 'GH', 'profile' => ['company_name' => 'EduReach', 'tagline' => 'Quality education for all', 'description' => 'EduReach delivers offline-first educational content to students in underserved communities using solar-powered tablets and local learning facilitators.', 'sector' => 'edtech', 'stage' => 'mvp', 'city' => 'Accra', 'team_size' => 8]],
            ['name' => 'SolarNow Uganda', 'email' => 'hello@solarnow.demo', 'country' => 'UG', 'profile' => ['company_name' => 'SolarNow', 'tagline' => 'Clean energy, pay as you go', 'description' => 'SolarNow provides affordable solar home systems to off-grid households in East Africa through a pay-as-you-go financing model.', 'sector' => 'cleantech', 'stage' => 'growth', 'city' => 'Kampala', 'team_size' => 32]],
            ['name' => 'Sendy Logistics', 'email' => 'ops@sendydemo.com', 'country' => 'KE', 'profile' => ['company_name' => 'SwiftMove', 'tagline' => 'Last-mile delivery, redefined', 'description' => 'SwiftMove is an on-demand logistics platform that connects businesses with a network of verified drivers for same-day delivery across East Africa.', 'sector' => 'logistics', 'stage' => 'early_traction', 'city' => 'Nairobi', 'team_size' => 24]],
            ['name' => 'Jumia Foods Clone', 'email' => 'ceo@afrimarket.demo', 'country' => 'NG', 'profile' => ['company_name' => 'AfriMarket', 'tagline' => 'Shop local, ship anywhere', 'description' => 'AfriMarket is a cross-border e-commerce platform enabling African artisans and merchants to sell their goods to a global audience.', 'sector' => 'ecommerce', 'stage' => 'mvp', 'city' => 'Lagos', 'team_size' => 12]],
            ['name' => 'CivicTech Egypt', 'email' => 'founder@civictech.demo', 'country' => 'EG', 'profile' => ['company_name' => 'CivicPulse', 'tagline' => 'Citizen engagement, simplified', 'description' => 'CivicPulse is a govtech platform that enables municipalities to collect citizen feedback, manage service requests, and improve public service delivery.', 'sector' => 'govtech', 'stage' => 'idea', 'city' => 'Cairo', 'team_size' => 4]],
            ['name' => 'AfriNews Senegal', 'email' => 'editor@afrinews.demo', 'country' => 'SN', 'profile' => ['company_name' => 'AfriNews', 'tagline' => 'African stories, African voices', 'description' => 'AfriNews is a digital media platform delivering verified local news in 12 African languages, with a focus on underreported stories from rural communities.', 'sector' => 'media', 'stage' => 'early_traction', 'city' => 'Dakar', 'team_size' => 15]],
            ['name' => 'LegalAid Rwanda', 'email' => 'cto@legalaid.demo', 'country' => 'RW', 'profile' => ['company_name' => 'LegalAid RW', 'tagline' => 'Legal help for everyone', 'description' => 'LegalAid RW is an AI-powered legal assistance platform that provides affordable legal advice and document preparation services to individuals and SMEs in Rwanda.', 'sector' => 'legaltech', 'stage' => 'mvp', 'city' => 'Kigali', 'team_size' => 6]],
            ['name' => 'PropCheck South Africa', 'email' => 'hello@propcheck.demo', 'country' => 'ZA', 'profile' => ['company_name' => 'PropCheck', 'tagline' => 'Rent smart, live better', 'description' => 'PropCheck is a proptech platform that uses data analytics to help renters find verified, affordable housing and helps landlords reduce vacancies and fraud.', 'sector' => 'proptech', 'stage' => 'early_traction', 'city' => 'Cape Town', 'team_size' => 9]],
            ['name' => 'ClimateMapper Ethiopia', 'email' => 'ceo@climatemapper.demo', 'country' => 'ET', 'profile' => ['company_name' => 'ClimateMapper', 'tagline' => 'Climate intelligence for Africa', 'description' => 'ClimateMapper provides AI-driven climate risk assessments and adaptation tools for governments, agribusinesses, and communities across the Horn of Africa.', 'sector' => 'climate', 'stage' => 'mvp', 'city' => 'Addis Ababa', 'team_size' => 7]],
            ['name' => 'DigiHealth Cameroon', 'email' => 'founder@digihealth.demo', 'country' => 'CM', 'profile' => ['company_name' => 'DigiHealth', 'tagline' => 'Healthcare at your fingertips', 'description' => 'DigiHealth connects patients in rural Cameroon with qualified doctors through telemedicine, making quality healthcare accessible and affordable.', 'sector' => 'healthtech', 'stage' => 'idea', 'city' => 'Douala', 'team_size' => 3]],
            ['name' => 'FarmLink Tanzania', 'email' => 'info@farmlink.demo', 'country' => 'TZ', 'profile' => ['company_name' => 'FarmLink', 'tagline' => 'Smart farming for smallholders', 'description' => 'FarmLink provides precision agriculture tools and weather data services to smallholder farmers in Tanzania, helping them increase yields and reduce crop losses.', 'sector' => 'agritech', 'stage' => 'early_traction', 'city' => 'Dar es Salaam', 'team_size' => 14]],
            ['name' => 'EduPay Nigeria', 'email' => 'ceo@edupay.demo', 'country' => 'NG', 'profile' => ['company_name' => 'EduPay', 'tagline' => 'Invest in education, pay in installments', 'description' => 'EduPay is an edtech-fintech hybrid that enables African students to access quality education through income-share agreements and micro-loans.', 'sector' => 'fintech', 'stage' => 'growth', 'city' => 'Abuja', 'team_size' => 28]],
            ['name' => 'GreenFreight Morocco', 'email' => 'ops@greenfreight.demo', 'country' => 'MA', 'profile' => ['company_name' => 'GreenFreight', 'tagline' => 'Eco-friendly freight, zero compromise', 'description' => 'GreenFreight is building North Africa\'s first carbon-neutral freight network using electric vehicles and AI-optimized routing.', 'sector' => 'cleantech', 'stage' => 'mvp', 'city' => 'Casablanca', 'team_size' => 11]],
            ['name' => 'JusticeConnect Kenya', 'email' => 'info@justiceconnect.demo', 'country' => 'KE', 'profile' => ['company_name' => 'JusticeConnect', 'tagline' => 'Access to justice for all', 'description' => 'JusticeConnect is a legaltech platform that matches low-income Kenyans with pro bono lawyers and legal aid organizations using AI-powered intake forms.', 'sector' => 'legaltech', 'stage' => 'idea', 'city' => 'Mombasa', 'team_size' => 4]],
            ['name' => 'HomeBase Egypt', 'email' => 'ceo@homebase.demo', 'country' => 'EG', 'profile' => ['company_name' => 'HomeBase', 'tagline' => 'Find your perfect home in Cairo', 'description' => 'HomeBase uses machine learning to match Cairo homebuyers and renters with verified properties, reducing search time from weeks to hours.', 'sector' => 'proptech', 'stage' => 'early_traction', 'city' => 'Cairo', 'team_size' => 18]],
            ['name' => 'WasteNot Rwanda', 'email' => 'founder@wastenot.demo', 'country' => 'RW', 'profile' => ['company_name' => 'WasteNot', 'tagline' => 'Turning waste into wealth', 'description' => 'WasteNot is a circular economy platform that connects businesses with waste collectors and recyclers to divert plastic and organic waste from landfills.', 'sector' => 'climate', 'stage' => 'mvp', 'city' => 'Kigali', 'team_size' => 8]],
            ['name' => 'BroadcastAfrica Ghana', 'email' => 'hello@broadcastafrica.demo', 'country' => 'GH', 'profile' => ['company_name' => 'BroadcastAfrica', 'tagline' => 'Podcast Africa\'s stories', 'description' => 'BroadcastAfrica is a podcast-first media company that trains and distributes content from African creators, reaching 2M+ listeners monthly.', 'sector' => 'media', 'stage' => 'growth', 'city' => 'Accra', 'team_size' => 20]],
            ['name' => 'TrustID Ivory Coast', 'email' => 'ceo@trustid.demo', 'country' => 'CI', 'profile' => ['company_name' => 'TrustID', 'tagline' => 'Digital identity for the unbanked', 'description' => 'TrustID creates portable digital identities for informal economy workers, enabling them to access financial services and government programs.', 'sector' => 'fintech', 'stage' => 'early_traction', 'city' => 'Abidjan', 'team_size' => 16]],
            ['name' => 'MedSupply Uganda', 'email' => 'ops@medsupply.demo', 'country' => 'UG', 'profile' => ['company_name' => 'MedSupply', 'tagline' => 'Medical supplies, on demand', 'description' => 'MedSupply is a B2B marketplace that helps Ugandan clinics and hospitals procure affordable, verified medical supplies directly from manufacturers.', 'sector' => 'healthtech', 'stage' => 'mvp', 'city' => 'Kampala', 'team_size' => 10]],
            ['name' => 'QuickClass Zambia', 'email' => 'founder@quickclass.demo', 'country' => 'ZM', 'profile' => ['company_name' => 'QuickClass', 'tagline' => 'Learn a skill, earn a living', 'description' => 'QuickClass provides mobile-first vocational training for young Zambians in high-demand trades like plumbing, coding, and tailoring.', 'sector' => 'edtech', 'stage' => 'idea', 'city' => 'Lusaka', 'team_size' => 5]],
            ['name' => 'SoilSense Ethiopia', 'email' => 'cto@soilsense.demo', 'country' => 'ET', 'profile' => ['company_name' => 'SoilSense', 'tagline' => 'Soil intelligence for better yields', 'description' => 'SoilSense deploys IoT sensors and AI models to give Ethiopian farmers real-time soil health data and personalized fertilizer recommendations.', 'sector' => 'agritech', 'stage' => 'mvp', 'city' => 'Addis Ababa', 'team_size' => 9]],
            ['name' => 'startup@demo.com', 'email' => 'startup@demo.com', 'country' => 'RW', 'profile' => ['company_name' => 'DemoStartup', 'tagline' => 'Demo startup for SANIA testing', 'description' => 'This is a demo startup account used for testing the SANIA platform. It showcases the startup experience from application to acceptance.', 'sector' => 'fintech', 'stage' => 'mvp', 'city' => 'Kigali', 'team_size' => 3]],
        ];

        $startupUsers = [];
        foreach ($startupData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => Role::Startup,
                    'country' => $data['country'],
                    'is_active' => true,
                ]
            );
            $p = $data['profile'];
            StartupProfile::firstOrCreate(['user_id' => $user->id], [
                'user_id' => $user->id,
                'company_name' => $p['company_name'],
                'slug' => Str::slug($p['company_name']),
                'tagline' => $p['tagline'],
                'description' => $p['description'],
                'sector' => $p['sector'],
                'stage' => $p['stage'],
                'country' => $data['country'],
                'city' => $p['city'],
                'team_size' => $p['team_size'],
                'founded_year' => rand(2018, 2024),
            ]);
            ActivityLog::record('user.registered', $user, ['role' => 'startup'], $user->id);
            $startupUsers[] = $user;
        }

        // Applications — 30 spread across incubators
        $statuses = [
            ApplicationStatus::Pending,
            ApplicationStatus::UnderReview,
            ApplicationStatus::Accepted,
            ApplicationStatus::Rejected,
        ];
        $weights = [4, 3, 2, 1]; // bias toward pending

        $used = [];
        $created = 0;
        foreach ($startupUsers as $startup) {
            if ($created >= 30) break;
            $incubator = $incubatorUsers[array_rand($incubatorUsers)];
            $key = $startup->id . '-' . $incubator->id;
            if (isset($used[$key])) continue;
            $used[$key] = true;

            $statusIndex = array_rand(array_filter($weights));
            $status = $statuses[$statusIndex];

            $app = Application::firstOrCreate(
                ['startup_id' => $startup->id, 'incubator_id' => $incubator->id],
                [
                    'status' => $status,
                    'motivation' => 'We are applying to ' . $incubator->incubatorProfile?->organization_name . ' because we believe your mentorship and network will help us scale across Africa. Our solution addresses a critical gap in the ' . $startup->startupProfile?->sector . ' sector, and we have seen strong early traction with real users. We are looking for support in product development, fundraising strategy, and regional market access.',
                    'pitch_deck_url' => rand(0, 1) ? 'https://docs.google.com/presentation/d/demo' : null,
                    'reviewer_notes' => in_array($status, [ApplicationStatus::Accepted, ApplicationStatus::Rejected]) ? 'Strong team with relevant domain expertise. ' . ($status === ApplicationStatus::Accepted ? 'Recommended for acceptance.' : 'Market size concerns.') : null,
                    'reviewed_at' => in_array($status, [ApplicationStatus::Accepted, ApplicationStatus::Rejected]) ? now()->subDays(rand(1, 30)) : null,
                    'reviewed_by' => in_array($status, [ApplicationStatus::Accepted, ApplicationStatus::Rejected]) ? $incubator->id : null,
                ]
            );

            ActivityLog::record('application.submitted', $app, ['startup' => $startup->name, 'incubator' => $incubator->name], $startup->id);
            $created++;
        }
    }
}
