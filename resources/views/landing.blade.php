<x-landing-layout>
    <section class="flex h-48">
        <img src={{ asset( 'lavender_wide.jpg' ) }} class="object-cover w-full h-full relative z-0"/>
    </section>
    <section class="text-gray-600 body-font">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="container mx-auto flex px-5 py-16 md:flex-row flex-col items-center">
                <div class="lg:flex-grow lg:pr-24 md:pr-16 flex flex-col md:items-start text-left mb-16 md:mb-0 items-center gap-y-2">
                <h1 class="title-font sm:text-4xl text-3xlfont-medium text-gray-900">
                    Welcome to the home of the TOP DD Network Randomized Controlled Trial!
                </h1>
                <p>We are now accepting patient-therapist pairs for a randomized controlled trial (RCT) of the newest version of our Network program. </p>
                <p>This program is designed to help patients with ongoing dissociation (i.e., the dissociative disorders, the dissociative subtype of PTSD) learn how to get and feel safer, including learning healing-focused ways to manage and reduce emotional overwhelm, symptoms of PTSD, and dissociation.</p>
                <div class="">The TOP DD Network study enabled DD patients around the world to participate in a web-based educational program informed by the results of our previous studies. Specifically developed to help patients get and feel safer, the study provided education about DD and healing-focused skills for managing intense emotions, PTSD and dissociative symptoms, and urges to engage in risky, unhealthy, or unsafe behaviors. </div>
                <div class="">Study participants were given access to the secure website that hosted the study’s educational program, including a series of brief (5-15 minute) educational videos for patients and therapists, and weekly questions and written and practice exercises to be completed by patients. </div>
                <div class="">Participation was associated with reduced emotional distress, reduced PTSD symptoms and dissociation, and higher adaptive capacities, especially among those with the most pronounced dissociation at intake. Improvements in self-harm among the most self-injurious patients were particularly striking. </div>
                <div class="">This study (the TOP DD Network RCT) will test the newest version of the program, which has been refined based on the feedback of previous program participants.</div>
                <div class="">Participation eligibility:
                    <ul class="list-disc ml-8">
                        <li>The Patient-Therapist team must have been working together in treatment for at least three months.</li>
                        <li>Patients must be over the age of 18, and must meet criteria for one of the following disorders: Other Specified Dissociative Disorder, Dissociative Identity Disorder (DID), Posttraumatic stress disorder, dissociative subtype (PTSD-D), and/or complex PTSD. </li>
                        <li>The Patient and Therapist must agree that the patient needs to learn more about the topics that are the focus of this program (getting and feeling safer and learning how to manage emotions, PTSD symptoms, and dissociation). </li>
                        <li>Participants must be able to tolerate non-detailed references to childhood and adult trauma; safety struggles and underlying reasons for these struggles; dissociation; and occasional brief discussions of “parts of self” (a term referring to dissociative self-states, which will only apply to some participants). </li>
                        <li>The Patient and Therapist must each agree to watch the educational videos. </li>
                        <li>Patients must agree to do the written reflections and experiential exercises that accompany the videos. (We will not collect the written exercises, but encourage patients to share these with their therapists.)</li>
                        <li>Both Patient and Therapist must have reliable high-speed access to the Internet (and if using a phone for access, have a data plan that can accommodate heavy data use). </li>
                        <li>The Patient and Therapist must be aware of the possibility of being randomly assigned to a six-month waiting period before they will be given access to the study’s materials. They agree that they are willing to participate in the study regardless of whether they are randomly assigned to the six-month waiting period or immediate access to the study’s materials.</li>
                        <li>The Patient and Therapist must each provide an email address to receive links to study materials and surveys. We highly recommend that participants use an email address that does not contain their name or other identifying information. The TOP DD research team will not see or be able to access participants’ email addresses; the study’s website will automatically send emails with survey links at timed intervals so that the researchers will not manually send emails, nor see email addresses.</li>
                        <li>Both Patient and Therapist must be able to read English.</li>
                        <li>If the Patient or Therapist decides to withdraw from the study, both members of the team will be withdrawn. If either the Patient or Therapist decides to withdraw from the study, they can do so for any reason by filling out the Discontinuation Survey. </li>
                        <li>If the Therapist and Patient discontinue treatment together, both will be withdrawn from the study. (Unfortunately, it is not possible for Patients to re-enroll in the study after withdrawing, even if the Patient begins treatment with a new therapist.) </li>
                        <li>Therapists can participate with only one patient.</li>
                        </ul>
                </div>
                <div class="">If you are a therapist and believe you and one of your patients may qualify, please click the link below to create an account and take the screening survey. </div>
                <div class="">If you are a patient and believe you and your therapist may qualify, please contact your therapist and ask them to create an account on this website and take the screening survey.</div>
                <div class="flex justify-center">
                    <x-nav-button-primary :href="route('register')" class="text-base">
                        Create a therapist account
                    </x-nav-button-primary>
                </div>
                </div>
            </div>
        </div>
    </section>
</x-landing-layout>
