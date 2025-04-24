Hospital Bed Management System Logic
Focus solely on managing hospital beds (WardBed), without expanding into room management. Below is the detailed database design, workflow, and actor roles.

1️⃣ Database Design (Optimized from the current ERD)
⚡ Key Tables:
WardBed

WardBedID (Primary Key)

WardID (Foreign Key - optional if linking to ward areas)

BedNumber (Bed identifier)

Status (ENUM: available, occupied, maintenance)

PatientWardAllocation

AllocationID (Primary Key)

PatientID (Foreign Key)

WardBedID (Foreign Key)

AllocationDate (Date assigned)

DischargeDate (Date released)

💡 Optional Table:
WardBedHistory (Track bed usage history)

HistoryID

WardBedID

PatientID

FromDate

ToDate

Note

2️⃣ Workflow
🎯 Objectives:
Manage bed status in real-time.

Track bed assignments and discharges.

Ensure optimal utilization of available beds.

3️⃣ Actor Roles & Responsibilities
👨‍⚕️ Doctor:
Assign beds to patients when hospitalization is required.

During consultation, if hospitalization is needed, the doctor requests a bed.

The system checks for available beds.

Doctor either selects a bed or sends a request to Admin.

Monitor patients' bed assignments.

View a list of admitted patients and their assigned beds.

👨‍💼 Admin:
Manage Bed Inventory (WardBed):

Add / Edit / Remove beds.

Update bed status:

available: Ready for use.

occupied: Currently assigned to a patient.

maintenance: Under cleaning or repair.

Allocate Beds:

Process doctor’s requests or manually assign beds.

Record allocation in PatientWardAllocation.

Update bed Status to occupied.

Discharge Beds:

Upon patient discharge:

Update DischargeDate.

Change bed status back to available or maintenance if cleaning is needed.

Reporting:

Monitor:

Number of available, occupied, and maintenance beds.

Bed usage history.

🧑‍🦽 Patient:
No direct interaction with the bed management system.

Can view assigned bed information, such as:

"You are assigned to Bed No. 12".

Estimated discharge date (if provided by Doctor/Admin).

4️⃣ Detailed Process Flow
➤ Step 1: Check Available Beds
Admin or Doctor reviews the list of beds where Status = available.

➤ Step 2: Assign Bed

Create a record in PatientWardAllocation:

PatientID, WardBedID, AllocationDate = NOW().

Update WardBed:

Set Status = occupied.

➤ Step 3: Discharge Process

When patient is discharged:

Update DischargeDate in PatientWardAllocation.

Change WardBed.Status to available or maintenance.

➤ Step 4: Bed Maintenance

Admin sets bed status to maintenance for cleaning or repairs.

5️⃣ Example Scenario
Doctor examines Patient A ➜ Hospitalization required.

Doctor checks available beds or sends a request.

Admin assigns Bed No. 12 to Patient A.

System:

Records the allocation.

Updates WardBedID 12 status to occupied.

Upon discharge:

Admin updates discharge date.

Bed 12 status changes to maintenance.

After cleaning, Admin sets status back to available.

6️⃣ Suggested UI/UX Design
Admin Dashboard:
Bed list with status filters.

"Allocate Bed" button.

Quick reports:

Total beds.

Occupied beds.

Available beds.

Beds under maintenance.

Doctor View:
List of admitted patients.

Display patient names and assigned bed numbers.

Patient View:
Notification:
"You are assigned to Bed No. 12".