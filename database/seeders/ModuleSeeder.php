<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\ModuleOffering;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        // Catalog grouped by Type -> Pathway
        $catalog = [
            'CT' => [
                'software_systems' => [
                    ['code' => 'CTEC 31013', 'title' => 'Web Programming II', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31023', 'title' => 'Mobile Application Development', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31032', 'title' => 'ICT for Business', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31042', 'title' => 'Python Programming', 'year' => 3, 'semester' => 1],
                    ['code' => 'SWST 31022', 'title' => 'Requirement Engineering', 'year' => 3, 'semester' => 1],
                    ['code' => 'SWST 31032', 'title' => 'Applied Information Systems', 'year' => 3, 'semester' => 1],
                    ['code' => 'ENPR 31042', 'title' => 'Principles and Practices of Management and Technology Management', 'year' => 3, 'semester' => 1],

                    ['code' => 'GTEC 32012', 'title' => 'Project Management', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTEC 32012', 'title' => 'Human Computer Interaction', 'year' => 3, 'semester' => 2],
                    ['code' => 'SWST 32012', 'title' => 'System Analysis and Design', 'year' => 3, 'semester' => 2],
                    ['code' => 'SWST 32022', 'title' => 'Quality Assurance', 'year' => 3, 'semester' => 2],
                    ['code' => 'SWST 32033', 'title' => 'Advanced Databases', 'year' => 3, 'semester' => 2],
                    ['code' => 'SWST 32043', 'title' => 'Software Architecture and Concepts', 'year' => 3, 'semester' => 2],

                    ['code' => 'CTEC 41016', 'title' => 'Industrial Training', 'year' => 4, 'semester' => 1],

                    ['code' => 'CTEC 43018', 'title' => 'Project', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTEC 44022', 'title' => 'Software and Hardware Optimization Techniques', 'year' => 4, 'semester' => 2],
                    ['code' => 'SWST 41022', 'title' => 'Applied Internet of Things', 'year' => 4, 'semester' => 2],
                    ['code' => 'SWST 44032', 'title' => 'Scientific Communication', 'year' => 4, 'semester' => 2],
                    ['code' => 'SWST 44042', 'title' => 'Speech Interfaces', 'year' => 4, 'semester' => 2],
                    ['code' => 'SWST 44053', 'title' => 'Software Modelling', 'year' => 4, 'semester' => 2],
                    ['code' => 'SWST 41062', 'title' => 'Enterprise Application Development', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44013', 'title' => 'Entrepreneurship and Small Business Management', 'year' => 4, 'semester' => 2],
                ],

                'networking' => [
                    ['code' => 'CTEC 31013', 'title' => 'Web Programming II', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31023', 'title' => 'Mobile Application Development', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31032', 'title' => 'ICT for Business', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31042', 'title' => 'Python Programming', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTNT 31012', 'title' => 'Introduction to Telecommunication', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTNT 31022', 'title' => 'Wireless and Mobile Communication', 'year' => 3, 'semester' => 1],
                    ['code' => 'ENPR 31042', 'title' => 'Principles and Practices of Management and Technology Management', 'year' => 3, 'semester' => 1],

                    ['code' => 'GTEC 32012', 'title' => 'Project Management', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTEC 32023', 'title' => 'Internet of Things', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTNT 32012', 'title' => 'Optical Fibre Communications and Satellite Communications', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTNT 32032', 'title' => 'Virtualization and Cloud Computing', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTNT 32042', 'title' => 'Advanced Communication Networks', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTNT 32051', 'title' => 'Cyber Security Laboratory', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTNT 32062', 'title' => 'Mobile Computing', 'year' => 3, 'semester' => 2],

                    ['code' => 'CTEC 41016', 'title' => 'Industrial Training', 'year' => 4, 'semester' => 1],

                    ['code' => 'CTEC 43018', 'title' => 'Project', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTNT 44021', 'title' => 'Advanced Networking Laboratory', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTNT 44032', 'title' => 'Network and System Administration', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTNT 44042', 'title' => 'Advanced Wireless and Mobile Communication', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTNT 44053', 'title' => 'Network and Information Security', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTNT 44062', 'title' => 'Security Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTNT 47073', 'title' => 'Distributed Computing', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44043', 'title' => 'Entrepreneurship and Small Business Management', 'year' => 4, 'semester' => 2],
                ],

                'gaming' => [
                    // Year 3 - Sem 1
                    ['code' => 'CTEC 31013', 'title' => 'Web Programming II', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31023', 'title' => 'Mobile Application Development', 'year' => 3, 'semester' => 1],
                    ['code' => 'CTEC 31032', 'title' => 'ICT for Business', 'year' => 3, 'semester' => 1],
                    ['code' => 'GANI 31012', 'title' => 'Data Structures for Game Development', 'year' => 3, 'semester' => 1],
                    ['code' => 'GANI 31022', 'title' => 'Introduction to 3D Modelling', 'year' => 3, 'semester' => 1],
                    ['code' => 'GANI 31032', 'title' => 'Game Design and Development', 'year' => 3, 'semester' => 1],
                    ['code' => 'ENPR 31042', 'title' => 'Principles and Practices of Management and Technology Management', 'year' => 3, 'semester' => 1],

                    // Year 3 - Sem 2
                    ['code' => 'GTEC 32012', 'title' => 'Project Management', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTEC 32012', 'title' => 'Human Computer Interaction', 'year' => 3, 'semester' => 2],
                    ['code' => 'CTEC 32013', 'title' => 'Advanced 3D Modelling Workshop', 'year' => 3, 'semester' => 2],
                    ['code' => 'GANI 32024', 'title' => 'Mathematics for Modelling and Rendering', 'year' => 3, 'semester' => 2],
                    ['code' => 'GANI 32033', 'title' => 'Animation for Game Development', 'year' => 3, 'semester' => 2],

                    // Year 4
                    ['code' => 'CTEC 41016', 'title' => 'Industrial Training', 'year' => 4, 'semester' => 1],

                    ['code' => 'CTEC 43018', 'title' => 'Project', 'year' => 4, 'semester' => 2],
                    ['code' => 'CTEC 44022', 'title' => 'Software and Hardware Optimization Techniques', 'year' => 4, 'semester' => 2],
                    ['code' => 'GANI 44033', 'title' => '3D Games Prototyping', 'year' => 4, 'semester' => 2],
                    ['code' => 'GANI 44043', 'title' => 'Real-Time 3D Techniques for Games', 'year' => 4, 'semester' => 2],
                    ['code' => 'GANI 44053', 'title' => 'Fundamentals of Virtual Reality', 'year' => 4, 'semester' => 2],
                    ['code' => 'GANI 44062', 'title' => 'Motion Graphics Workshop', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44043', 'title' => 'Entrepreneurship and Small Business Management', 'year' => 4, 'semester' => 2],
                ],
            ],

            'ET' => [
                'material' => [
                    // Year 3 - Sem 1
                    ['code' => 'ETEC 31013', 'title' => 'Programming in Python for Engineering Technology', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETEC 31023', 'title' => 'Fluid Mechanics and Fluid Systems', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETEC 31033', 'title' => 'Mechanics of Machines', 'year' => 3, 'semester' => 1],
                    ['code' => 'ENPR 31042', 'title' => 'Principles and Practices of Technology Management', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETMP 31213', 'title' => 'Chemical Process Technology', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETMP 31223', 'title' => 'Engineering Materials - II', 'year' => 3, 'semester' => 1],
                    // Year 3 - Sem 2
                    ['code' => 'ETEC 32012', 'title' => 'Machine Design with Computer Aided Design', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETEC 32022', 'title' => 'Manufacturing Systems and Computer Integrated Manufacturing', 'year' => 3, 'semester' => 2],
                    ['code' => 'ENPR 33033', 'title' => 'Innovations to Market', 'year' => 3, 'semester' => 2],
                    ['code' => 'GCPR 32041', 'title' => 'Professional Ethics and Practices', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETMP 32213', 'title' => 'Science of Engineering Materials', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETMP 32223', 'title' => 'Materials Processes in Industry - I', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETMP 32233', 'title' => 'Nanoscience and Nanomaterials', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETMP 32243', 'title' => 'Integrated Computational Materials Engineering', 'year' => 3, 'semester' => 2],
                    // Year 4
                    ['code' => 'GTEC 41016', 'title' => 'Industrial Training', 'year' => 4, 'semester' => 1],
                    ['code' => 'ETEC 43018', 'title' => 'Capstone Project', 'year' => 4, 'semester' => 1],
                    // Year 4 - Sem 2
                    ['code' => 'GCPR 44022', 'title' => 'Occupational Health and Safety', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44033', 'title' => 'Total Productive Maintenance (TPM)', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44043', 'title' => 'Entrepreneurship and Small Business Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44052', 'title' => 'Lean/Six Sigma Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETMP 44213', 'title' => 'Materials Processes in Industry - II', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETMP 44223', 'title' => 'Novel Engineering Materials and Next Generation Devices', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETMP 44233', 'title' => 'Materials Characterization and Testing Laboratory', 'year' => 4, 'semester' => 2],
                ],

                'sustainable' => [
                    // Year 3 - Sem 1
                    ['code' => 'ETEC 31013', 'title' => 'Programming in Python for Engineering Technology', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETEC 31023', 'title' => 'Fluid Mechanics and Fluid Systems', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETEC 31033', 'title' => 'Mechanics of Machines', 'year' => 3, 'semester' => 1],
                    ['code' => 'ENPR 31042', 'title' => 'Principles and Practices of Technology Management', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETST 31613', 'title' => 'Hydrology and Hydrogeology with Lab', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETST 31623', 'title' => 'Conventional and Alternative Energy Resources', 'year' => 3, 'semester' => 1],
                    // Year 3 - Sem 2
                    ['code' => 'ETEC 32012', 'title' => 'Machine Design with Computer Aided Design', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETEC 32022', 'title' => 'Manufacturing Systems and Computer Integrated Manufacturing', 'year' => 3, 'semester' => 2],
                    ['code' => 'ENPR 33033', 'title' => 'Innovations to Market', 'year' => 3, 'semester' => 2],
                    ['code' => 'GCPR 32041', 'title' => 'Professional Ethics and Practices', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETST 32613', 'title' => 'Energy Storage Technologies with Lab', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETST 32623', 'title' => 'Water and Wastewater Treatment', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETST 32633', 'title' => 'Soil and Solid Waste Treatment', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETST 32643', 'title' => 'Air and Air Pollution Control', 'year' => 3, 'semester' => 2],
                    // Year 4
                    ['code' => 'GTEC 41016', 'title' => 'Industrial Training', 'year' => 4, 'semester' => 1],
                    ['code' => 'ETEC 43018', 'title' => 'Capstone Project', 'year' => 4, 'semester' => 1],
                    // Year 4 - Sem 2
                    ['code' => 'GCPR 44022', 'title' => 'Occupational Health and Safety', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44033', 'title' => 'Total Productive Maintenance (TPM)', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44043', 'title' => 'Entrepreneurship and Small Business Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44052', 'title' => 'Lean/Six Sigma Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETST 44613', 'title' => 'Monitoring and Assessment of Sustainability', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETST 44623', 'title' => 'Sustainable Facilities and Operations', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETST 44633', 'title' => 'Geographical Information Systems for Sustainability with Laboratory', 'year' => 4, 'semester' => 2],
                ],

                'automation' => [
                    // Year 3 - Sem 1
                    ['code' => 'ETEC 31013', 'title' => 'Programming in Python for Engineering Technology', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETEC 31023', 'title' => 'Fluid Mechanics and Fluid Systems', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETEC 31033', 'title' => 'Mechanics of Machines', 'year' => 3, 'semester' => 1],
                    ['code' => 'ENPR 31042', 'title' => 'Principles and Practices of Technology Management', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETIA 31413', 'title' => 'Introduction to Industrial Automation', 'year' => 3, 'semester' => 1],
                    ['code' => 'ETIA 31423', 'title' => 'Introduction to Microprocessors and Embedded Systems', 'year' => 3, 'semester' => 1],
                    // Year 3 - Sem 2
                    ['code' => 'ETEC 32012', 'title' => 'Machine Design with Computer Aided Design', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETEC 32022', 'title' => 'Manufacturing Systems and Computer Integrated Manufacturing', 'year' => 3, 'semester' => 2],
                    ['code' => 'ENPR 33033', 'title' => 'Innovations to Market', 'year' => 3, 'semester' => 2],
                    ['code' => 'GCPR 32041', 'title' => 'Professional Ethics and Practices', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETIA 32413', 'title' => 'Introduction to Robotics in Manufacturing', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETIA 32423', 'title' => 'Process Instrumentation and Control', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETIA 32433', 'title' => 'Industrial Automation Networks', 'year' => 3, 'semester' => 2],
                    ['code' => 'ETIA 32443', 'title' => 'Embedded Systems and Applications', 'year' => 3, 'semester' => 2],
                    // Year 4
                    ['code' => 'GTEC 41016', 'title' => 'Industrial Training', 'year' => 4, 'semester' => 1],
                    ['code' => 'ETEC 43018', 'title' => 'Capstone Project', 'year' => 4, 'semester' => 1],
                    // Year 4 - Sem 2
                    ['code' => 'GCPR 44022', 'title' => 'Occupational Health and Safety', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44033', 'title' => 'Total Productive Maintenance (TPM)', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44043', 'title' => 'Entrepreneurship and Small Business Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'ENPR 44052', 'title' => 'Lean/Six Sigma Management', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETIA 44413', 'title' => 'Computer Integrated Manufacturing', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETIA 44423', 'title' => 'Industrial Motion Control', 'year' => 4, 'semester' => 2],
                    ['code' => 'ETIA 44433', 'title' => 'Computer Aided Manufacturing with Lab', 'year' => 4, 'semester' => 2],
                ],
            ],

            'CS' => [
                'cyber_security' => [
                    ['code' => 'CSEC 31012', 'title' => 'Applied Cryptography', 'year' => 3, 'semester' => 1],
                    ['code' => 'CSEC 31022', 'title' => 'Data and Systems Security', 'year' => 3, 'semester' => 1],

                    ['code' => 'CSEC 32022', 'title' => 'Advanced Computer Communication and Networking', 'year' => 3, 'semester' => 2],
                    ['code' => 'CSEC 32032', 'title' => 'Network Security', 'year' => 3, 'semester' => 2],
                    ['code' => 'CSEC 32012', 'title' => 'Wireless Communications and Networking', 'year' => 3, 'semester' => 2],

                    ['code' => 'CSEC 44022', 'title' => 'Information Security Management and Auditing', 'year' => 4, 'semester' => 1],
                    ['code' => 'CSEC 44032', 'title' => 'Cyber Crime and Forensics', 'year' => 4, 'semester' => 1],
                    ['code' => 'CSEC 44042', 'title' => 'Security Analytics', 'year' => 4, 'semester' => 1],
                    ['code' => 'CSEC 44052', 'title' => 'Cyber Laws and Standards', 'year' => 4, 'semester' => 1],
                    ['code' => 'CSEC 44062', 'title' => 'Ethical Hacking and Vulnerability Analysis', 'year' => 4, 'semester' => 1],
                    ['code' => 'CSEC 44072', 'title' => 'Secure Software Engineering', 'year' => 4, 'semester' => 2],
                    ['code' => 'CSEC 44082', 'title' => 'Information & Coding Theory', 'year' => 4, 'semester' => 2],
                    ['code' => 'CSEC 44092', 'title' => 'Mobile & IoT Security', 'year' => 4, 'semester' => 2],
                    ['code' => 'CSEC 44102', 'title' => 'Advanced Cryptography', 'year' => 4, 'semester' => 2],
                ],

                'data_science' => [
                    ['code' => 'CSCI 31022', 'title' => 'Machine Learning and Pattern Recognition', 'year' => 3, 'semester' => 1],

                    ['code' => 'CSCI 32092', 'title' => 'Data Mining and Warehousing', 'year' => 3, 'semester' => 2],
                    ['code' => 'DSCI 32012', 'title' => 'Advanced Database Applications', 'year' => 3, 'semester' => 2],
                    ['code' => 'CSCI 32083', 'title' => 'Stochastic Processes', 'year' => 3, 'semester' => 2],

                    ['code' => 'DSCI 44012', 'title' => 'Python for Data Science', 'year' => 4, 'semester' => 1],
                    ['code' => 'DSCI 44022', 'title' => 'Data Visualization', 'year' => 4, 'semester' => 1],
                    ['code' => 'DSCI 44033', 'title' => 'Big Data Analytics', 'year' => 4, 'semester' => 1],
                    ['code' => 'DSCI 44042', 'title' => 'NoSQL Databases', 'year' => 4, 'semester' => 1],
                    ['code' => 'DSCI 44052', 'title' => 'Time Series Analysis for Data Science', 'year' => 4, 'semester' => 1],
                    ['code' => 'DSCI 44062', 'title' => 'Big Data Architecture and Management', 'year' => 4, 'semester' => 1],
                    ['code' => 'DSCI 44072', 'title' => 'Geographical Information Systems', 'year' => 4, 'semester' => 1],
                ],

                'ai' => [
                    ['code' => 'AINT 31012', 'title' => 'Natural Language Processing', 'year' => 3, 'semester' => 1],
                    ['code' => 'AINT 31022', 'title' => 'Deductive Reasoning and Logic Programming', 'year' => 3, 'semester' => 1],

                    ['code' => 'AINT 32012', 'title' => 'Digital Image Processing and Computer Vision', 'year' => 3, 'semester' => 2],
                    ['code' => 'AINT 32022', 'title' => 'Complex Systems & Agent Technology', 'year' => 3, 'semester' => 2],

                    ['code' => 'AINT 44012', 'title' => 'Artificial Neural Networks', 'year' => 4, 'semester' => 1],
                    ['code' => 'AINT 44022', 'title' => 'Fuzzy Logic', 'year' => 4, 'semester' => 1],
                    ['code' => 'AINT 44032', 'title' => 'Deep Learning', 'year' => 4, 'semester' => 1],
                    ['code' => 'AINT 44042', 'title' => 'Machine Translation', 'year' => 4, 'semester' => 1],
                    ['code' => 'AINT 44052', 'title' => 'Intelligent Autonomous Robotics', 'year' => 4, 'semester' => 2],
                    ['code' => 'AINT 44062', 'title' => 'Computational Cognitive Science', 'year' => 4, 'semester' => 2],
                    ['code' => 'AINT 44072', 'title' => 'Introduction to Virtual Reality', 'year' => 4, 'semester' => 2],
                ],

                'scientific_computing' => [
                    ['code' => 'SCOM 31013', 'title' => 'Numerical Analysis and Scientific Programming', 'year' => 3, 'semester' => 1],
                    ['code' => 'SCOM 31022', 'title' => 'Scientific Visualization', 'year' => 3, 'semester' => 1],
                    ['code' => 'SCOM 31032', 'title' => 'Mathematical Modeling', 'year' => 3, 'semester' => 1],

                    ['code' => 'SCOM 32012', 'title' => 'Parallel Computing', 'year' => 3, 'semester' => 2],

                    ['code' => 'SCOM 44012', 'title' => 'High Performance Computing', 'year' => 4, 'semester' => 1],
                    ['code' => 'SCOM 44022', 'title' => 'Advanced Numerical Analysis and Scientific Programming', 'year' => 4, 'semester' => 1],
                    ['code' => 'SCOM 44033', 'title' => 'Survey of Materials Simulation Methods', 'year' => 4, 'semester' => 1],
                    ['code' => 'SCOM 44043', 'title' => 'Finite Element Methods in Scientific Computing', 'year' => 4, 'semester' => 2],
                    ['code' => 'SCOM 44052', 'title' => 'Graphics Processing Unit Programming', 'year' => 4, 'semester' => 2],
                ],
            ],
        ];

        foreach ($catalog as $type => $pathways) {
            foreach ($pathways as $pathway => $mods) {
                foreach ($mods as $m) {
                    // Create or update the module record by unique code
                    $module = Module::updateOrCreate(
                        ['code' => $m['code']],
                        [
                            'title'       => $m['title'],
                            'description' => $m['description'] ?? null,
                            'credits'     => $m['credits'] ?? null,
                        ]
                    );

                    // Link it to a specific type/pathway/year/semester
                    ModuleOffering::firstOrCreate([
                        'module_id' => $module->id,
                        'type'      => $type,
                        'pathway'   => $pathway,
                        'year'      => (int) $m['year'],
                        'semester'  => (int) $m['semester'],
                    ]);
                }
            }
        }
    }
}
