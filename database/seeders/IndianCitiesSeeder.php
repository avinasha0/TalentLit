<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndianCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Andhra Pradesh
            ['name' => 'Hyderabad', 'state' => 'Andhra Pradesh', 'state_code' => 'AP', 'latitude' => 17.3850, 'longitude' => 78.4867],
            ['name' => 'Visakhapatnam', 'state' => 'Andhra Pradesh', 'state_code' => 'AP', 'latitude' => 17.6868, 'longitude' => 83.2185],
            ['name' => 'Vijayawada', 'state' => 'Andhra Pradesh', 'state_code' => 'AP', 'latitude' => 16.5062, 'longitude' => 80.6480],
            ['name' => 'Guntur', 'state' => 'Andhra Pradesh', 'state_code' => 'AP', 'latitude' => 16.3067, 'longitude' => 80.4365],
            ['name' => 'Tirupati', 'state' => 'Andhra Pradesh', 'state_code' => 'AP', 'latitude' => 13.6288, 'longitude' => 79.4192],

            // Arunachal Pradesh
            ['name' => 'Itanagar', 'state' => 'Arunachal Pradesh', 'state_code' => 'AR', 'latitude' => 27.0844, 'longitude' => 93.6053],

            // Assam
            ['name' => 'Guwahati', 'state' => 'Assam', 'state_code' => 'AS', 'latitude' => 26.1445, 'longitude' => 91.7362],
            ['name' => 'Silchar', 'state' => 'Assam', 'state_code' => 'AS', 'latitude' => 24.8167, 'longitude' => 92.8000],
            ['name' => 'Dibrugarh', 'state' => 'Assam', 'state_code' => 'AS', 'latitude' => 27.4833, 'longitude' => 94.9000],

            // Bihar
            ['name' => 'Patna', 'state' => 'Bihar', 'state_code' => 'BR', 'latitude' => 25.5941, 'longitude' => 85.1376],
            ['name' => 'Gaya', 'state' => 'Bihar', 'state_code' => 'BR', 'latitude' => 24.7955, 'longitude' => 85.0000],
            ['name' => 'Bhagalpur', 'state' => 'Bihar', 'state_code' => 'BR', 'latitude' => 25.2445, 'longitude' => 86.9718],

            // Chhattisgarh
            ['name' => 'Raipur', 'state' => 'Chhattisgarh', 'state_code' => 'CG', 'latitude' => 21.2514, 'longitude' => 81.6296],
            ['name' => 'Bhilai', 'state' => 'Chhattisgarh', 'state_code' => 'CG', 'latitude' => 21.2090, 'longitude' => 81.4285],
            ['name' => 'Bilaspur', 'state' => 'Chhattisgarh', 'state_code' => 'CG', 'latitude' => 22.0800, 'longitude' => 82.1500],

            // Goa
            ['name' => 'Panaji', 'state' => 'Goa', 'state_code' => 'GA', 'latitude' => 15.4909, 'longitude' => 73.8278],
            ['name' => 'Margao', 'state' => 'Goa', 'state_code' => 'GA', 'latitude' => 15.2730, 'longitude' => 73.9583],

            // Gujarat
            ['name' => 'Ahmedabad', 'state' => 'Gujarat', 'state_code' => 'GJ', 'latitude' => 23.0225, 'longitude' => 72.5714],
            ['name' => 'Surat', 'state' => 'Gujarat', 'state_code' => 'GJ', 'latitude' => 21.1702, 'longitude' => 72.8311],
            ['name' => 'Vadodara', 'state' => 'Gujarat', 'state_code' => 'GJ', 'latitude' => 22.3072, 'longitude' => 73.1812],
            ['name' => 'Rajkot', 'state' => 'Gujarat', 'state_code' => 'GJ', 'latitude' => 22.3039, 'longitude' => 70.8022],
            ['name' => 'Bhavnagar', 'state' => 'Gujarat', 'state_code' => 'GJ', 'latitude' => 21.7645, 'longitude' => 72.1519],

            // Haryana
            ['name' => 'Chandigarh', 'state' => 'Haryana', 'state_code' => 'HR', 'latitude' => 30.7333, 'longitude' => 76.7794],
            ['name' => 'Faridabad', 'state' => 'Haryana', 'state_code' => 'HR', 'latitude' => 28.4089, 'longitude' => 77.3178],
            ['name' => 'Gurgaon', 'state' => 'Haryana', 'state_code' => 'HR', 'latitude' => 28.4595, 'longitude' => 77.0266],
            ['name' => 'Panipat', 'state' => 'Haryana', 'state_code' => 'HR', 'latitude' => 29.3909, 'longitude' => 76.9635],

            // Himachal Pradesh
            ['name' => 'Shimla', 'state' => 'Himachal Pradesh', 'state_code' => 'HP', 'latitude' => 31.1048, 'longitude' => 77.1734],
            ['name' => 'Dharamshala', 'state' => 'Himachal Pradesh', 'state_code' => 'HP', 'latitude' => 32.2190, 'longitude' => 76.3234],

            // Jharkhand
            ['name' => 'Ranchi', 'state' => 'Jharkhand', 'state_code' => 'JH', 'latitude' => 23.3441, 'longitude' => 85.3096],
            ['name' => 'Jamshedpur', 'state' => 'Jharkhand', 'state_code' => 'JH', 'latitude' => 22.8046, 'longitude' => 86.2029],
            ['name' => 'Dhanbad', 'state' => 'Jharkhand', 'state_code' => 'JH', 'latitude' => 23.7957, 'longitude' => 86.4304],

            // Karnataka
            ['name' => 'Bangalore', 'state' => 'Karnataka', 'state_code' => 'KA', 'latitude' => 12.9716, 'longitude' => 77.5946],
            ['name' => 'Mysore', 'state' => 'Karnataka', 'state_code' => 'KA', 'latitude' => 12.2958, 'longitude' => 76.6394],
            ['name' => 'Hubli', 'state' => 'Karnataka', 'state_code' => 'KA', 'latitude' => 15.3647, 'longitude' => 75.1240],
            ['name' => 'Mangalore', 'state' => 'Karnataka', 'state_code' => 'KA', 'latitude' => 12.9141, 'longitude' => 74.8560],

            // Kerala
            ['name' => 'Thiruvananthapuram', 'state' => 'Kerala', 'state_code' => 'KL', 'latitude' => 8.5241, 'longitude' => 76.9366],
            ['name' => 'Kochi', 'state' => 'Kerala', 'state_code' => 'KL', 'latitude' => 9.9312, 'longitude' => 76.2673],
            ['name' => 'Kozhikode', 'state' => 'Kerala', 'state_code' => 'KL', 'latitude' => 11.2588, 'longitude' => 75.7804],
            ['name' => 'Thrissur', 'state' => 'Kerala', 'state_code' => 'KL', 'latitude' => 10.5276, 'longitude' => 76.2144],

            // Madhya Pradesh
            ['name' => 'Bhopal', 'state' => 'Madhya Pradesh', 'state_code' => 'MP', 'latitude' => 23.2599, 'longitude' => 77.4126],
            ['name' => 'Indore', 'state' => 'Madhya Pradesh', 'state_code' => 'MP', 'latitude' => 22.7196, 'longitude' => 75.8577],
            ['name' => 'Gwalior', 'state' => 'Madhya Pradesh', 'state_code' => 'MP', 'latitude' => 26.2183, 'longitude' => 78.1828],
            ['name' => 'Jabalpur', 'state' => 'Madhya Pradesh', 'state_code' => 'MP', 'latitude' => 23.1815, 'longitude' => 79.9864],

            // Maharashtra
            ['name' => 'Mumbai', 'state' => 'Maharashtra', 'state_code' => 'MH', 'latitude' => 19.0760, 'longitude' => 72.8777],
            ['name' => 'Pune', 'state' => 'Maharashtra', 'state_code' => 'MH', 'latitude' => 18.5204, 'longitude' => 73.8567],
            ['name' => 'Nagpur', 'state' => 'Maharashtra', 'state_code' => 'MH', 'latitude' => 21.1458, 'longitude' => 79.0882],
            ['name' => 'Thane', 'state' => 'Maharashtra', 'state_code' => 'MH', 'latitude' => 19.2183, 'longitude' => 72.9781],
            ['name' => 'Nashik', 'state' => 'Maharashtra', 'state_code' => 'MH', 'latitude' => 19.9975, 'longitude' => 73.7898],
            ['name' => 'Aurangabad', 'state' => 'Maharashtra', 'state_code' => 'MH', 'latitude' => 19.8762, 'longitude' => 75.3433],

            // Manipur
            ['name' => 'Imphal', 'state' => 'Manipur', 'state_code' => 'MN', 'latitude' => 24.8170, 'longitude' => 93.9368],

            // Meghalaya
            ['name' => 'Shillong', 'state' => 'Meghalaya', 'state_code' => 'ML', 'latitude' => 25.5788, 'longitude' => 91.8933],

            // Mizoram
            ['name' => 'Aizawl', 'state' => 'Mizoram', 'state_code' => 'MZ', 'latitude' => 23.7271, 'longitude' => 92.7176],

            // Nagaland
            ['name' => 'Kohima', 'state' => 'Nagaland', 'state_code' => 'NL', 'latitude' => 25.6751, 'longitude' => 94.1086],

            // Odisha
            ['name' => 'Bhubaneswar', 'state' => 'Odisha', 'state_code' => 'OR', 'latitude' => 20.2961, 'longitude' => 85.8245],
            ['name' => 'Cuttack', 'state' => 'Odisha', 'state_code' => 'OR', 'latitude' => 20.4625, 'longitude' => 85.8830],
            ['name' => 'Rourkela', 'state' => 'Odisha', 'state_code' => 'OR', 'latitude' => 22.2604, 'longitude' => 84.8536],

            // Punjab
            ['name' => 'Chandigarh', 'state' => 'Punjab', 'state_code' => 'PB', 'latitude' => 30.7333, 'longitude' => 76.7794],
            ['name' => 'Ludhiana', 'state' => 'Punjab', 'state_code' => 'PB', 'latitude' => 30.9010, 'longitude' => 75.8573],
            ['name' => 'Amritsar', 'state' => 'Punjab', 'state_code' => 'PB', 'latitude' => 31.6340, 'longitude' => 74.8723],
            ['name' => 'Jalandhar', 'state' => 'Punjab', 'state_code' => 'PB', 'latitude' => 31.3260, 'longitude' => 75.5762],

            // Rajasthan
            ['name' => 'Jaipur', 'state' => 'Rajasthan', 'state_code' => 'RJ', 'latitude' => 26.9124, 'longitude' => 75.7873],
            ['name' => 'Jodhpur', 'state' => 'Rajasthan', 'state_code' => 'RJ', 'latitude' => 26.2389, 'longitude' => 73.0243],
            ['name' => 'Udaipur', 'state' => 'Rajasthan', 'state_code' => 'RJ', 'latitude' => 24.5854, 'longitude' => 73.7125],
            ['name' => 'Kota', 'state' => 'Rajasthan', 'state_code' => 'RJ', 'latitude' => 25.2138, 'longitude' => 75.8648],
            ['name' => 'Bikaner', 'state' => 'Rajasthan', 'state_code' => 'RJ', 'latitude' => 28.0229, 'longitude' => 73.3119],

            // Sikkim
            ['name' => 'Gangtok', 'state' => 'Sikkim', 'state_code' => 'SK', 'latitude' => 27.3314, 'longitude' => 88.6138],

            // Tamil Nadu
            ['name' => 'Chennai', 'state' => 'Tamil Nadu', 'state_code' => 'TN', 'latitude' => 13.0827, 'longitude' => 80.2707],
            ['name' => 'Coimbatore', 'state' => 'Tamil Nadu', 'state_code' => 'TN', 'latitude' => 11.0168, 'longitude' => 76.9558],
            ['name' => 'Madurai', 'state' => 'Tamil Nadu', 'state_code' => 'TN', 'latitude' => 9.9252, 'longitude' => 78.1198],
            ['name' => 'Tiruchirappalli', 'state' => 'Tamil Nadu', 'state_code' => 'TN', 'latitude' => 10.7905, 'longitude' => 78.7047],
            ['name' => 'Salem', 'state' => 'Tamil Nadu', 'state_code' => 'TN', 'latitude' => 11.6643, 'longitude' => 78.1460],

            // Telangana
            ['name' => 'Hyderabad', 'state' => 'Telangana', 'state_code' => 'TG', 'latitude' => 17.3850, 'longitude' => 78.4867],
            ['name' => 'Warangal', 'state' => 'Telangana', 'state_code' => 'TG', 'latitude' => 17.9689, 'longitude' => 79.5941],
            ['name' => 'Nizamabad', 'state' => 'Telangana', 'state_code' => 'TG', 'latitude' => 18.6715, 'longitude' => 78.0938],

            // Tripura
            ['name' => 'Agartala', 'state' => 'Tripura', 'state_code' => 'TR', 'latitude' => 23.8315, 'longitude' => 91.2862],

            // Uttar Pradesh
            ['name' => 'Lucknow', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 26.8467, 'longitude' => 80.9462],
            ['name' => 'Kanpur', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 26.4499, 'longitude' => 80.3319],
            ['name' => 'Agra', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 27.1767, 'longitude' => 78.0081],
            ['name' => 'Varanasi', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 25.3176, 'longitude' => 82.9739],
            ['name' => 'Allahabad', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 25.4358, 'longitude' => 81.8463],
            ['name' => 'Bareilly', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 28.3670, 'longitude' => 79.4304],
            ['name' => 'Meerut', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 28.9845, 'longitude' => 77.7064],
            ['name' => 'Ghaziabad', 'state' => 'Uttar Pradesh', 'state_code' => 'UP', 'latitude' => 28.6692, 'longitude' => 77.4538],

            // Uttarakhand
            ['name' => 'Dehradun', 'state' => 'Uttarakhand', 'state_code' => 'UK', 'latitude' => 30.3165, 'longitude' => 78.0322],
            ['name' => 'Haridwar', 'state' => 'Uttarakhand', 'state_code' => 'UK', 'latitude' => 29.9457, 'longitude' => 78.1642],

            // West Bengal
            ['name' => 'Kolkata', 'state' => 'West Bengal', 'state_code' => 'WB', 'latitude' => 22.5726, 'longitude' => 88.3639],
            ['name' => 'Asansol', 'state' => 'West Bengal', 'state_code' => 'WB', 'latitude' => 23.6739, 'longitude' => 86.9524],
            ['name' => 'Siliguri', 'state' => 'West Bengal', 'state_code' => 'WB', 'latitude' => 26.7271, 'longitude' => 88.3953],
            ['name' => 'Durgapur', 'state' => 'West Bengal', 'state_code' => 'WB', 'latitude' => 23.5204, 'longitude' => 87.3119],

            // Union Territories
            ['name' => 'New Delhi', 'state' => 'Delhi', 'state_code' => 'DL', 'latitude' => 28.6139, 'longitude' => 77.2090],
            ['name' => 'Puducherry', 'state' => 'Puducherry', 'state_code' => 'PY', 'latitude' => 11.9416, 'longitude' => 79.8083],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
