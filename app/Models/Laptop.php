<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'price',
        'ram_gb',
        'storage_gb',
        'display_size',
        'processor_score',
        'vga_score',
        'performa_komposit',
        'battery_hours',
        'weight_kg',
        'mobility_score',
        'category',
        'condition',
        'image',
    ];

    protected $casts = [
        'price' => 'integer',
        'ram_gb' => 'integer',
        'storage_gb' => 'integer',
        'display_size' => 'float',
        'performa_komposit' => 'integer',
        'battery_hours' => 'float',
        'weight_kg' => 'float',
    ];

    // Alias Accessors untuk skema kriteria Indonesia
    public function getNamaAttribute(): string
    {
        return $this->name;
    }

    public function getMerekAttribute(): ?string
    {
        return $this->brand;
    }

    public function getHargaAttribute(): ?int
    {
        return $this->price;
    }

    public function getRamAttribute(): ?int
    {
        return $this->ram_gb;
    }

    public function getStorageAttribute(): ?int
    {
        return $this->storage_gb;
    }

    public function getBateraiAttribute(): ?float
    {
        return $this->battery_hours;
    }

    public function getBeratAttribute(): ?float
    {
        return $this->weight_kg;
    }

    public function getKondisiAttribute(): string
    {
        return $this->condition ?: 'baru';
    }

    public function getPerformaAttribute(): int
    {
        if ($this->performa_komposit) {
            return (int) $this->performa_komposit;
        }

        $cpu = $this->processor_score ?: 50;
        $vga = $this->vga_score ?: 50;
        return (int) round(($cpu + $vga) / 2);
    }

    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
        }

        $brand = strtolower($this->brand ?? '');
        $name = strtolower($this->name ?? '');

        // 1. Acer
        if (str_contains($brand, 'acer')) {
            if (str_contains($name, 'nitro') || str_contains($name, 'predator') || str_contains($name, 'aspire 7')) {
                return asset('images/laptops/acer-nitro.jpg');
            }
            if (str_contains($name, 'travelmate') || str_contains($name, 'extensa')) {
                return asset('images/laptops/black-business.jpg');
            }
            return asset('images/laptops/acer-aspire.jpg');
        }

        // 2. ASUS
        if (str_contains($brand, 'asus')) {
            if (str_contains($name, 'tuf') || str_contains($name, 'rog') || str_contains($name, 'zephyrus') || str_contains($name, 'gaming')) {
                return asset('images/laptops/asus-tuf.jpg');
            }
            return asset('images/laptops/asus-vivobook.jpg');
        }

        // 3. Lenovo
        if (str_contains($brand, 'lenovo')) {
            if (str_contains($name, 'thinkpad') || str_contains($name, 't14') || str_contains($name, 't480') || str_contains($name, 't470') || str_contains($name, 't460') || str_contains($name, 't440') || str_contains($name, 'x1 carbon') || str_contains($name, 'x13') || str_contains($name, 'x260') || str_contains($name, 'x250') || str_contains($name, 'l380') || str_contains($name, 'l490') || str_contains($name, 'l15') || str_contains($name, 'e590') || str_contains($name, '500e')) {
                return asset('images/laptops/thinkpad.jpg');
            }
            if (str_contains($name, 'gaming') || str_contains($name, 'legion')) {
                return asset('images/laptops/gaming-laptop.jpg');
            }
            return asset('images/laptops/ideapad-slim.jpg');
        }

        // 4. Dell
        if (str_contains($brand, 'dell')) {
            if (str_contains($name, 'xps')) {
                return asset('images/laptops/dell-xps.jpg');
            }
            if (str_contains($name, 'alienware') || str_contains($name, 'g15') || str_contains($name, 'gaming')) {
                return asset('images/laptops/gaming-laptop.jpg');
            }
            return asset('images/laptops/dell-latitude.jpg');
        }

        // 5. HP
        if (str_contains($brand, 'hp')) {
            if (str_contains($name, 'elitebook')) {
                return asset('images/laptops/hp-elitebook.jpg');
            }
            if (str_contains($name, 'probook') || str_contains($name, '4420s')) {
                return asset('images/laptops/black-business.jpg');
            }
            if (str_contains($name, 'victus') || str_contains($name, 'omen') || str_contains($name, 'gaming')) {
                return asset('images/laptops/gaming-laptop.jpg');
            }
            return asset('images/laptops/hp-laptop.jpg');
        }

        // 6. MSI
        if (str_contains($brand, 'msi')) {
            return asset('images/laptops/msi-gaming.jpg');
        }

        // 7. Axioo
        if (str_contains($brand, 'axioo')) {
            if (str_contains($name, 'pongo')) {
                return asset('images/laptops/gaming-laptop.jpg');
            }
            return asset('images/laptops/axioo-hype.jpg');
        }

        // 8. ADVAN
        if (str_contains($brand, 'advan')) {
            if (str_contains($name, 'pixwar') || str_contains($name, 'gaming')) {
                return asset('images/laptops/gaming-laptop.jpg');
            }
            return asset('images/laptops/advan-laptop.jpg');
        }

        // 9. Apple / MacBook
        if (str_contains($brand, 'macbook') || str_contains($brand, 'apple') || str_contains($name, 'macbook') || str_contains($name, 'air')) {
            return asset('images/laptops/macbook.jpg');
        }

        // 10. Toshiba / Fujitsu
        if (str_contains($brand, 'toshiba') || str_contains($brand, 'fujitsu')) {
            return asset('images/laptops/black-business.jpg');
        }

        // 11. Infinix / Samsung / Others
        if (str_contains($brand, 'infinix') || str_contains($brand, 'samsung')) {
            return asset('images/laptops/silver-ultrabook.jpg');
        }

        return asset('images/laptops/silver-ultrabook.jpg');
    }

    // Accessor Link Pembelian Marketplace (Shopee, Tokopedia, Facebook Marketplace, Blibli, Bukalapak)
    public function getSearchQueryAttribute(): string
    {
        return trim(($this->brand ?? '') . ' ' . ($this->name ?? ''));
    }

    public function getShopeeUrlAttribute(): string
    {
        return 'https://shopee.co.id/search?keyword=' . urlencode($this->search_query);
    }

    public function getTokopediaUrlAttribute(): string
    {
        return 'https://www.tokopedia.com/search?st=product&q=' . urlencode($this->search_query);
    }

    public function getFacebookUrlAttribute(): string
    {
        return 'https://www.facebook.com/marketplace/search/?query=' . urlencode($this->search_query);
    }

    public function getBlibliUrlAttribute(): string
    {
        return 'https://www.blibli.com/cari/' . urlencode($this->search_query);
    }

    public function getBukalapakUrlAttribute(): string
    {
        return 'https://www.bukalapak.com/products?search%5Bkeywords%5D=' . urlencode($this->search_query);
    }

    public function hasilTopsis(): HasMany
    {
        return $this->hasMany(HasilTopsis::class, 'laptop_id');
    }

    public function penjelasanAi(): HasMany
    {
        return $this->hasMany(PenjelasanAi::class, 'laptop_id');
    }
}
