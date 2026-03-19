<?php

$products = [
    'gym-access' => [
        'name' => 'PulseFit Gym Access',
        'tagline' => 'Open gym, strength zones, and smart workout tracking.',
        'description' => 'Full access to PulseFit training floors, including strength, cardio, and functional zones. Every visit can be logged in the PulseFit app so you can see your volume, PRs, and trends over time. Track your lifts, monitor progress, and watch your strength grow.',
        'image' => '../assets/gym.jpg',
    ],
    'yoga-flow' => [
        'name' => 'Yoga Flow Studio',
        'tagline' => 'Mobility, balance, and stress relief in one studio.',
        'description' => 'Guided yoga sessions designed for better posture, mobility, and recovery. Choose from relaxing evening flows, mobility blocks for lifters, and energy-building morning classes. Build flexibility and reduce stress.',
        'image' => '../assets/Yoga.jpg',
    ],
    'pilates-core' => [
        'name' => 'Pilates Core Program',
        'tagline' => 'Low-impact training that builds strong, stable core strength.',
        'description' => 'A structured Pilates program combining mat and equipment-based sessions. Ideal for rebuilding core strength, improving alignment, and supporting performance. Perfect for injury recovery and long-term development.',
        'image' => '../assets/pilate.jpg',
    ],
    'nutrition-coaching' => [
        'name' => '1:1 Nutrition Coaching',
        'tagline' => 'Simple, sustainable nutrition tailored to your goals.',
        'description' => 'Work directly with a PulseFit coach on targets, habits, and meal structure. We focus on realistic routines that work with your schedule—not rigid rules or crash diets. Get personalized meal plans and ongoing support.',
        'image' => '../assets/Nutrition.jpg',
    ],
    'habit-coaching' => [
        'name' => 'Habit & Accountability Coaching',
        'tagline' => 'Stay consistent with weekly check-ins and simple action steps.',
        'description' => 'Turn good intentions into consistent routines with a dedicated coach. Each week you get a short check-in, clear next steps, and in-app reminders that keep things moving. Build lasting habits that stick.',
        'image' => '../assets/mental_health.jpg',
    ],
    'team-training' => [
        'name' => 'Small Group Team Training',
        'tagline' => 'Train with a coach and a small group that pushes you.',
        'description' => 'Coach-led strength and conditioning sessions in a small group format. You get personalized guidance plus the energy and accountability of a team environment. Limited size ensures maximum attention.',
        'image' => '../assets/group_training.jpg',
    ],
    'recovery-lab' => [
        'name' => 'Recovery Lab Sessions',
        'tagline' => 'Dedicated time for mobility, stretch, and guided recovery.',
        'description' => 'Structured recovery blocks that combine guided stretching, light mobility work, and breathing drills. Ideal for rest days, deload weeks, or post-competition reset. Accelerate recovery and prevent injury.',
        'image' => '../assets/Yoga.jpg',
    ],
    'onboarding' => [
        'name' => 'Starter Onboarding Package',
        'tagline' => 'Three sessions to map your plan, movements, and starting points.',
        'description' => 'A focused onboarding track that includes movement screening, goal setting, and a starter training plan in the PulseFit app. Perfect for brand-new or returning members. Get off to the right start.',
        'image' => '../assets/group_training.jpg',
    ],
    'corporate-wellness' => [
        'name' => 'Corporate Wellness Programs',
        'tagline' => 'Flexible fitness and wellness solutions for teams and workplaces.',
        'description' => 'Bring PulseFit to your organization with customized class blocks, wellness challenges, and nutrition workshops. All paired with simple reporting you can share with leadership. Boost employee health and morale.',
        'image' => '../assets/mental_health.jpg',
    ],
    'app-membership' => [
        'name' => 'PulseFit App Membership',
        'tagline' => 'Train anywhere with app-based programs and habit tracking.',
        'description' => 'Access on-demand programs, progress tracking, and habit streaks directly inside the PulseFit app. Ideal for remote members or anyone who prefers to train from home. Complete flexibility, full functionality.',
        'image' => '../assets/group_training.jpg',
    ],
];

function pulsefit_product_url(string $slug): string
{
    return 'product-' . $slug . '.php';
}
