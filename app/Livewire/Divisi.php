<?php

namespace App\Livewire;

use Livewire\Component;

class Divisi extends Component
{
    public function render()
    {
        $divisiUtama = [
            [
                'id' => 1,
                'nama' => 'Mechanical',
                'icon' => 'fa-cog',
                'gradient' => 'tw-from-blue-400 tw-to-cyan-500',
                'border' => 'tw-border-blue-500/30',
                'text' => 'tw-text-blue-300',
                'bg' => 'tw-bg-blue-500/10',
                'deskripsi' => 'Divisi Mechanical fokus pada perancangan, pembuatan, dan pengembangan sistem mekanik robot. Meliputi desain struktur, pemilihan material, sistem penggerak, dan integrasi komponen mekanik untuk menghasilkan robot yang efisien dan handal.',
                'keahlian' => [
                    'CAD Design (SolidWorks, Fusion 360)',
                    'Fabrication & Assembly',
                    'Material Selection',
                    'Mechanical Systems Integration',
                    '3D Printing & CNC Machining'
                ]
            ],
            [
                'id' => 2,
                'nama' => 'Electrical',
                'icon' => 'fa-bolt',
                'gradient' => 'tw-from-yellow-400 tw-to-orange-500',
                'border' => 'tw-border-yellow-500/30',
                'text' => 'tw-text-yellow-300',
                'bg' => 'tw-bg-yellow-500/10',
                'deskripsi' => 'Divisi Electrical bertanggung jawab atas sistem kelistrikan dan elektronika robot. Meliputi desain PCB, pemilihan komponen elektronik, sistem power, sensor integration, dan troubleshooting sistem elektrikal untuk memastikan robot beroperasi dengan optimal.',
                'keahlian' => [
                    'PCB Design (Eagle, KiCAD, Altium)',
                    'Microcontroller Programming (Arduino, STM32)',
                    'Sensor Integration & Calibration',
                    'Power Management Systems',
                    'Circuit Analysis & Troubleshooting'
                ]
            ],
            [
                'id' => 3,
                'nama' => 'Programming',
                'icon' => 'fa-code',
                'gradient' => 'tw-from-purple-400 tw-to-pink-500',
                'border' => 'tw-border-purple-500/30',
                'text' => 'tw-text-purple-300',
                'bg' => 'tw-bg-purple-500/10',
                'deskripsi' => 'Divisi Programming mengembangkan software dan algoritma untuk mengontrol robot. Meliputi embedded programming, pengembangan algoritma control, computer vision, autonomous systems, dan integrasi antara hardware dan software untuk menghasilkan robot yang intelligent.',
                'keahlian' => [
                    'Embedded C/C++ Programming',
                    'Python for Robotics & AI',
                    'Computer Vision (OpenCV)',
                    'ROS (Robot Operating System)',
                    'Algorithm Development & Optimization'
                ]
            ]
        ];

        return view('livewire.divisi', [
            'divisiUtama' => $divisiUtama
        ])->extends('components.layouts.welcome', [
            'title' => 'PR PNJ - Psychorobotic - Divisi'
        ]);
    }
}
