@extends('layouts.base')

@section('content')

    <!DOCTYPE html>

    <html class="light" lang="en">

    <head>

        <meta charset="utf-8" />

        <meta content="width=device-width, initial-scale=1.0" name="viewport" />

        <title>Help Center</title>

        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700&amp;display=swap"

            rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

        <style>

            .material-symbols-outlined {

                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;

            }

            details>summary::-webkit-details-marker {

                display: none;

            }

          

        </style>

        <script id="tailwind-config">

            tailwind.config = {

                darkMode: "class",

                theme: {

                    extend: {

                        colors: {

                            "primary": "#005A9C",

                            "background-light": "#F0F4F8",

                            "background-dark": "#101922",

                            "accent": "#FF6F00",

                            "text-light-primary": "#212121",

                            "text-light-secondary": "#616161",

                            "text-dark-primary": "#E0E0E0",

                            "text-dark-secondary": "#9E9E9E",

                            "card-light": "#FFFFFF",

                            "card-dark": "#1E293B",

                            "border-light": "#E0E0E0",

                            "border-dark": "#334155",

                            "textGreen": "#10B981"

                        },

                        fontFamily: {

                            "display": ["Public Sans", "sans-serif"]

                        },

                        borderRadius: {

                            "DEFAULT": "0.5rem",

                            "lg": "0.75rem",

                            "xl": "1rem",

                            "full": "9999px"

                        },

                    },

                },

            }

        </script>

        <style>

            body {

                min-height: max(884px, 100dvh);

            }

        </style>

    </head>

    <body

        class="bg-background-light dark:bg-background-dark font-display text-text-light-primary dark:text-text-dark-primary">

      

        <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden pb-5">

            <header

                class="flex items-center bg-background-light dark:bg-background-dark p-4 pb-2 justify-between sticky top-0 z-10 border-b border-border-light dark:border-border-dark">

                <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-primary/10 active:bg-primary/20">

                    <span class="material-symbols-outlined">

                        arrow_back_ios_new

                    </span>

                </button>

                <h1 class="text-lg font-bold flex-1 text-center">Help Center</h1>

                <div class="size-10"></div>

            </header>

            <main class="flex-1">

                <div class="grid grid-cols-[repeat(auto-fit,minmax(150px,1fr))] gap-3 px-4 pt-4">

                    <div
                        class="flex flex-1 gap-3 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-4 flex-col items-start hover:border-primary dark:hover:border-primary transition-colors duration-200 cursor-pointer">

                        <span class="material-symbols-outlined text-accent text-2xl">help</span>

                        <div class="flex flex-col gap-1">

                            <h3 class="text-base font-bold">Query</h3>

                            <p class="text-sm font-normal text-text-light-secondary dark:text-text-dark-secondary">

                                Help With Issues</p>

                        </div>

                    </div>

                    <div

                        class="flex flex-1 gap-3 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-4 flex-col items-start hover:border-primary dark:hover:border-primary transition-colors duration-200 cursor-pointer">

                        <span class="material-symbols-outlined text-textGreen text-2xl">reviews</span>

                        
                            <a href="mailto:mohitkjain@ptu.ac.in" target="_blank">
                        <div class="flex flex-col gap-1">

                            <h3 class="text-base font-bold">Support</h3>

                            <p class="text-sm font-normal text-text-light-secondary dark:text-text-dark-secondary">

                                Email Support</p>

                        </div>
                        </a>

                    </div>

                </div>

              

                <h2

                    class="text-xl font-bold tracking-tight px-4 pb-3 pt-8 text-text-light-primary dark:text-text-dark-primary">

                    Frequently Asked Questions</h2>

                <div class="flex flex-col gap-2 px-4">

                    <details

                        class="group rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden">

                        <summary

                            class="flex cursor-pointer list-none items-center justify-between p-4 font-medium text-text-light-primary dark:text-text-dark-primary">

                            How do I reset my password?

                            <span

                                class="material-symbols-outlined transition-transform duration-300 group-open:rotate-180">expand_more</span>

                        </summary>

                        <div class="px-4 pb-4 text-text-light-secondary dark:text-text-dark-secondary">

                            You can reset your password by clicking the "Forgot Password" link on the login page. An email

                            will be sent to you with instructions on how to create a new password.

                        </div>

                    </details>

                    <details

                        class="group rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden">

                        <summary

                            class="flex cursor-pointer list-none items-center justify-between p-4 font-medium text-text-light-primary dark:text-text-dark-primary">

                            What is the eligibility criteria for X company?

                            <span

                                class="material-symbols-outlined transition-transform duration-300 group-open:rotate-180">expand_more</span>

                        </summary>

                        <div class="px-4 pb-4 text-text-light-secondary dark:text-text-dark-secondary">

                            Eligibility criteria vary by company and role. You can find detailed information on the specific

                            job posting page.

                        </div>

                    </details>

                    <details

                        class="group rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden">

                        <summary

                            class="flex cursor-pointer list-none items-center justify-between p-4 font-medium text-text-light-primary dark:text-text-dark-primary">

                            Where can I see my application status?

                            <span

                                class="material-symbols-outlined transition-transform duration-300 group-open:rotate-180">expand_more</span>

                        </summary>

                        <div class="px-4 pb-4 text-text-light-secondary dark:text-text-dark-secondary">

                            You can track the status of all your applications in the "My Applications" section of your

                            dashboard.

                        </div>

                    </details>

                    <details

                        class="group rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden">

                        <summary

                            class="flex cursor-pointer list-none items-center justify-between p-4 font-medium text-text-light-primary dark:text-text-dark-primary">

                            How do I upload my resume?

                            <span

                                class="material-symbols-outlined transition-transform duration-300 group-open:rotate-180">expand_more</span>

                        </summary>

                        <div class="px-4 pb-4 text-text-light-secondary dark:text-text-dark-secondary">

                            Navigate to your profile page and look for the "Upload Resume" button. We recommend using a PDF

                            format for best compatibility.

                        </div>

                    </details>

                </div>

                <div class="p-4 pt-8 pb-6">

                    <div

                        class="flex mb-3 flex-col items-center gap-4 rounded-xl bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark p-6 text-center">

                        <h3 class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">Still need help?

                        </h3>

                        <p class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Can't find the answer

                            you're looking for? Don't worry, we're here to help.</p>

                            <a href="mailto:mohitkjain@ptu.ac.in" target="_blank">
                        <button

                            class="flex items-center justify-center gap-2 rounded-lg bg-accent px-6 py-3 text-base font-bold text-white shadow-sm transition-transform duration-200 hover:scale-105 active:scale-95">

                            <span class="material-symbols-outlined">chat_bubble</span>

                            Contact Support

                        </button>
                        </a>

                    </div>
<section
                style="
                    background: linear-gradient(135deg, #0077b6, #009688);
                    color: #ffffff;
                    padding:5px;
                    text-align: center;
                    font-family: 'Poppins', sans-serif;
                    font-size: 15px;
                    letter-spacing: 0.3px;
                    border-bottom-left-radius: 32px;
                    border-top-right-radius: 32px;
                    box-shadow: 0 -2px 15px rgba(0,0,0,0.1);

                ">
                <p style="margin: 0;">
                    <span style="opacity: 0.9;">&copy; <strong>Copyright</strong> </span>
                    <a href="https://ptu.ac.in/" style="color:#fff; font-weight:600; text-decoration:none;">IKGPTU
                        
                        T&amp;P Cell</a>
                    <span style="opacity: 0.9;"> || Developed By </span>
                    <a href="https://birendrapandit.online"
                        style="color:#ffe66d; font-weight:600; text-decoration:none;">Birendra Pandit</a>
                </p>
            </section>

                </div>



            </main>






        </div>

      

   

    </body>

    </html>

@endsection
