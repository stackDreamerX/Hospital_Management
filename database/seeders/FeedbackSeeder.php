<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\User;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patient users
        $patients = User::where('RoleID', 'patient')->get();

        if ($patients->isEmpty()) {
            $this->command->info('No patients found. Skipping feedback seeding.');
            return;
        }

        // Categories for feedback
        $categories = ['doctor', 'facility', 'staff', 'treatment', 'overall'];

        // Departments
        $departments = ['Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics', 'Gynecology',
                       'Dermatology', 'Ophthalmology', 'Oncology', 'Radiology', 'Emergency'];

        // Doctor names (using a mix of common names)
        $doctorNames = [
            'Dr. Nguyen Van Anh',
            'Dr. Tran Minh Duc',
            'Dr. Le Thi Hong',
            'Dr. Pham Thanh Hai',
            'Dr. Vo Hoang Nam',
            'Dr. Dang Thu Huong',
            'Dr. Hoang Quoc Viet',
            'Dr. Bui Thi Lan',
            'Dr. Do Minh Tuan',
            'Dr. Nguyen Thi Mai'
        ];

        // Status options
        $statuses = ['pending', 'approved', 'rejected'];

        // Subjects for feedback
        $subjects = [
            'Great experience with my recent visit',
            'Need improvement in waiting times',
            'Excellent care from the medical team',
            'Issues with billing department',
            'Feedback on hospital facilities',
            'Outstanding service from nursing staff',
            'Concerns about medication instructions',
            'Appreciation for emergency care',
            'Suggestions for patient comfort',
            'Follow-up care experience',
            'Cleanliness of the hospital rooms',
            'Communication issues with staff',
            'Positive experience with surgery',
            'Feedback on hospital food quality',
            'Wait time concerns in emergency',
            'Excellent doctor consultation',
            'Issues with appointment scheduling',
            'Gratitude for life-saving care',
            'Feedback on hospital parking',
            'Experience with telehealth services'
        ];

        // Positive feedback messages
        $positiveMessages = [
            'I had an excellent experience during my recent visit. The staff was attentive and professional, and the doctor took time to explain everything clearly.',
            'The care I received was outstanding. Everyone from reception to nursing staff to doctors were kind, efficient, and knowledgeable.',
            'I want to express my gratitude to the entire team. The facility was clean, the process was smooth, and I felt well taken care of throughout my stay.',
            'My doctor was exceptional in explaining my condition and treatment options. I felt involved in the decision-making process and confident in the care I received.',
            'The nursing staff went above and beyond to ensure my comfort. They were responsive, compassionate, and truly made a difficult time much easier.',
            'I was impressed by how quickly I was seen and the thoroughness of my examination. The doctor was knowledgeable and addressed all my concerns.',
            'The hospital environment was clean, quiet, and conducive to healing. I appreciated the modern facilities and equipment used in my treatment.',
            'From admission to discharge, every step was handled professionally. I felt respected and valued as a patient throughout my entire stay.',
            'The follow-up care has been excellent. My questions are always answered promptly, and I feel supported in my recovery process.',
            'I had a complex procedure done and was nervous, but the medical team was reassuring and skilled. My recovery has been better than expected.'
        ];

        // Mixed/Neutral feedback messages
        $mixedMessages = [
            'Overall my experience was good, though there were some delays in getting test results. The medical care itself was excellent.',
            'The doctors and nurses were great, but the administrative process could use some improvement. I spent too long waiting for paperwork.',
            'I appreciate the quality of care I received, though I think communication between departments could be better coordinated.',
            'The medical team was excellent, but the facility itself is showing its age. Some updates to the patient rooms would be beneficial.',
            'My treatment was effective, but I would have appreciated more information about potential side effects before starting medication.',
            'The care was good, though the wait times were longer than expected. I understand the hospital is busy but better time management would help.',
            'I had a generally positive experience, though the food quality could definitely be improved for patients staying overnight.',
            'The medical staff was knowledgeable, but sometimes seemed rushed. I occasionally felt like my questions were hurried through.',
            'My procedure went well, but the discharge instructions were somewhat confusing. A clearer written guide would have been helpful.',
            'The care was competent, though the billing process afterward was complicated and difficult to navigate.'
        ];

        // Negative feedback messages
        $negativeMessages = [
            'I waited over two hours past my appointment time without any explanation or updates from staff. This is unacceptable for scheduled appointments.',
            'The communication between departments was poor, resulting in repeated tests and conflicting information from different doctors.',
            'I found the billing department extremely difficult to work with. My insurance claims were incorrectly filed multiple times despite my corrections.',
            'The night staff was inattentive to patient needs. My calls went unanswered for extended periods when I needed assistance.',
            'The room was not properly cleaned before my stay. I found trash from the previous patient and had to request cleaning multiple times.',
            'I received contradictory medical advice from different providers, which caused confusion and anxiety about my treatment plan.',
            'The parking situation is terrible. I had to walk a long distance while ill because there were no available spots near the entrance.',
            'My privacy concerns were not taken seriously. I overheard staff discussing my case in a public area where others could hear.',
            'The follow-up care has been non-existent. My calls to the clinic for post-procedure questions have gone unreturned.',
            'The doctor spent less than five minutes with me and seemed completely uninterested in my symptoms or concerns.'
        ];

        // Admin notes examples
        $adminNotes = [
            'Forwarded to department head for review',
            'Patient contacted for more details',
            'Issue resolved with patient',
            'Discussed in staff meeting',
            'No action required',
            'Follow-up scheduled with patient',
            'Training opportunity identified',
            'Process improvement implemented',
            'Compliment shared with team',
            null
        ];

        // Create 50 feedback entries
        for ($i = 0; $i < 50; $i++) {
            $rating = rand(1, 5);
            $category = $categories[array_rand($categories)];
            $status = $statuses[array_rand($statuses)];
            $isHighlighted = rand(0, 10) > 8; // 20% chance of being highlighted
            $isAnonymous = rand(0, 10) > 7; // 30% chance of being anonymous

            // Select message based on rating
            if ($rating >= 4) {
                $message = $positiveMessages[array_rand($positiveMessages)];
            } elseif ($rating >= 2) {
                $message = $mixedMessages[array_rand($mixedMessages)];
            } else {
                $message = $negativeMessages[array_rand($negativeMessages)];
            }

            // Randomize creation date within the last 3 months
            $createdAt = Carbon::now()->subDays(rand(0, 90));

            // If status is not pending, add admin review date
            $adminReviewedAt = null;
            if ($status !== 'pending') {
                $adminReviewedAt = (clone $createdAt)->addDays(rand(1, 5));
            }

            // Create the feedback
            Feedback::create([
                'user_id' => $patients->random()->UserID,
                'subject' => $subjects[array_rand($subjects)],
                'message' => $message,
                'rating' => $rating,
                'category' => $category,
                'department' => $departments[array_rand($departments)],
                'doctor_name' => $category === 'doctor' ? $doctorNames[array_rand($doctorNames)] : null,
                'status' => $status,
                'is_anonymous' => $isAnonymous,
                'is_highlighted' => $isHighlighted && $status === 'approved', // Only approved feedback can be highlighted
                'admin_notes' => $status !== 'pending' ? $adminNotes[array_rand($adminNotes)] : null,
                'admin_reviewed_at' => $adminReviewedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);
        }

        // Create a few detailed feedback entries with specific scenarios

        // Scenario 1: Very positive feedback about a doctor
        Feedback::create([
            'user_id' => $patients->random()->UserID,
            'subject' => 'Dr. Nguyen saved my life - Eternally grateful',
            'message' => "I cannot express enough gratitude for Dr. Nguyen Van Anh and his incredible team. I was admitted with severe chest pain, and they quickly diagnosed a life-threatening condition. The speed and precision with which they acted literally saved my life. Throughout my stay, Dr. Nguyen visited daily, explained everything in detail, and made sure I understood my treatment plan. The nursing staff was equally amazing - compassionate, attentive, and professional. This hospital sets the standard for medical care. I've recommended Dr. Nguyen to all my family and friends.",
            'rating' => 5,
            'category' => 'doctor',
            'department' => 'Cardiology',
            'doctor_name' => 'Dr. Nguyen Van Anh',
            'status' => 'approved',
            'is_anonymous' => false,
            'is_highlighted' => true,
            'admin_notes' => 'Shared with cardiology department. Dr. Nguyen received recognition award.',
            'admin_reviewed_at' => Carbon::now()->subDays(5),
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(15)
        ]);

        // Scenario 2: Constructive criticism about facilities
        Feedback::create([
            'user_id' => $patients->random()->UserID,
            'subject' => 'Suggestions for improving patient rooms',
            'message' => "While my medical care was excellent, I'd like to offer some constructive feedback about the patient rooms in the east wing. The rooms are quite outdated compared to other hospitals I've visited. Specific issues include: poor temperature control (my room was consistently too hot), limited electrical outlets for charging devices, uncomfortable visitor chairs, and outdated bathroom facilities. These may seem like small things, but they significantly impact patient comfort during longer stays. I hope this feedback helps in future renovation planning. Despite these issues, I want to emphasize that the care I received was top-notch.",
            'rating' => 3,
            'category' => 'facility',
            'department' => 'Orthopedics',
            'doctor_name' => null,
            'status' => 'approved',
            'is_anonymous' => false,
            'is_highlighted' => false,
            'admin_notes' => 'Forwarded to facilities management. East wing renovation scheduled for next fiscal year.',
            'admin_reviewed_at' => Carbon::now()->subDays(20),
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(30)
        ]);

        // Scenario 3: Complaint about administrative processes
        Feedback::create([
            'user_id' => $patients->random()->UserID,
            'subject' => 'Billing department needs serious improvement',
            'message' => "I'm writing to express my extreme frustration with the billing department. After my procedure three months ago, I've received multiple incorrect bills, had my insurance claims filed incorrectly twice, and spent hours on the phone trying to resolve these issues. Each time I call, I speak with someone new who has no record of my previous conversations. I've been threatened with collections despite having documentation that my insurance should cover these charges. The medical care I received was good, but this administrative nightmare has made me reconsider using your hospital in the future. Please address these systemic issues in your billing department.",
            'rating' => 1,
            'category' => 'staff',
            'department' => 'Billing',
            'doctor_name' => null,
            'status' => 'approved',
            'is_anonymous' => true,
            'is_highlighted' => false,
            'admin_notes' => 'Urgent review with billing department head. Patient contacted and issue resolved. Staff training implemented.',
            'admin_reviewed_at' => Carbon::now()->subDays(10),
            'created_at' => Carbon::now()->subDays(25),
            'updated_at' => Carbon::now()->subDays(25)
        ]);

        // Scenario 4: Feedback about emergency care
        Feedback::create([
            'user_id' => $patients->random()->UserID,
            'subject' => 'Outstanding emergency department',
            'message' => "I brought my 7-year-old daughter to the emergency department after a severe allergic reaction. From the moment we arrived, the care was exceptional. We were seen immediately, and the pediatric emergency team worked efficiently while keeping both my daughter and me calm. Dr. Tran explained everything clearly and followed up with us even after we were discharged. The child life specialist who brought toys and distraction during my daughter's treatment was especially appreciated. In a terrifying situation, your team provided not just medical care but true compassion. Thank you for having such a well-trained and caring emergency staff.",
            'rating' => 5,
            'category' => 'treatment',
            'department' => 'Emergency',
            'doctor_name' => 'Dr. Tran Minh Duc',
            'status' => 'approved',
            'is_anonymous' => false,
            'is_highlighted' => true,
            'admin_notes' => 'Shared with emergency department staff. Used in hospital newsletter.',
            'admin_reviewed_at' => Carbon::now()->subDays(15),
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20)
        ]);

        // Scenario 5: Feedback about telehealth
        Feedback::create([
            'user_id' => $patients->random()->UserID,
            'subject' => 'Telehealth service needs improvement',
            'message' => "I recently tried to use your telehealth service for a follow-up appointment. The experience was frustrating from start to finish. The platform was difficult to navigate, the video quality was poor, and the connection dropped three times during my consultation. When I finally connected with the doctor, he seemed rushed and unfamiliar with my case despite having access to my records. I ended up having to schedule an in-person appointment anyway, defeating the purpose of telehealth. Please invest in better technology and training for your telehealth services if you want patients to use this option.",
            'rating' => 2,
            'category' => 'overall',
            'department' => 'Telehealth',
            'doctor_name' => null,
            'status' => 'pending',
            'is_anonymous' => false,
            'is_highlighted' => false,
            'admin_notes' => null,
            'admin_reviewed_at' => null,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2)
        ]);
    }
}