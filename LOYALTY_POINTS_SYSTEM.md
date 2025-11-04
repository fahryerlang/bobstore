# 🎁 Loyalty Points System Documentation

## Overview
The loyalty points system allows members to earn points from purchases and redeem them for discounts on future transactions. The system distinguishes between regular customers and members, with members gaining exclusive benefits based on their tier level.

## Features Implemented

### 1. **Member Tier System**
- **Bronze** (0+ points): 1.0x earning multiplier 🥉
- **Silver** (500+ points): 1.2x earning multiplier 🥈
- **Gold** (2000+ points): 1.5x earning multiplier 🥇
- **Platinum** (5000+ points): 2.0x earning multiplier 💎

### 2. **Points Earning**
- **Base Rate**: 1 point per Rp 1,000 spent
- **Multiplier Applied**: Based on member tier level
- **Example**: 
  - Bronze member spending Rp 50,000 = 50 points (50,000 ÷ 1,000 × 1.0)
  - Platinum member spending Rp 50,000 = 100 points (50,000 ÷ 1,000 × 2.0)

### 3. **Points Redemption**
- **Conversion Rate**: 1 point = Rp 100 discount
- **Minimum Redemption**: 10 points (Rp 1,000 discount)
- **Maximum Redemption**: Cannot exceed total purchase amount or available points
- **Validation**: Prevents over-redemption that would make total negative

### 4. **Points Expiration**
- Points expire **1 year** after earning date
- Tracked in `point_transactions` table with `expires_at` timestamp
- Expired points are automatically excluded from available balance

## Database Schema

### Users Table (Modified)
```sql
- points (integer): Current available points balance
- member_level (enum): bronze, silver, gold, platinum
- member_since (timestamp): Date user became a member
```

### Point Transactions Table (New)
```sql
- id (bigint)
- user_id (foreign key to users)
- sale_id (nullable, foreign key to sales)
- type (enum): earn, redeem, expire, adjustment
- points (integer): Amount of points (positive for earn, negative for redeem/expire)
- balance_after (integer): Points balance after this transaction
- description (string): Human-readable description
- expires_at (nullable timestamp): For 'earn' transactions only
- created_at, updated_at
```

## Backend Implementation

### Service Layer: `LoyaltyPointService.php`
Located at: `app/Services/LoyaltyPointService.php`

**Key Methods:**
- `calculatePointsEarned($amount, $memberLevel)`: Calculate points earned from purchase
- `calculatePointsValue($points)`: Convert points to Rupiah value
- `awardPoints($userId, $saleId, $amount, $description)`: Award points after purchase
- `redeemPoints($userId, $points, $saleId, $description)`: Redeem points for discount
- `updateMemberLevel($userId)`: Automatically update member tier based on total points
- `getMemberLevelInfo($level)`: Get tier details (name, color, icon, multiplier)

**Constants:**
```php
const POINTS_PER_RUPIAH = 0.001; // 1 point per Rp 1,000
const RUPIAH_PER_POINT = 100;    // 1 point = Rp 100 discount
const MIN_REDEEM_POINTS = 10;
const POINTS_EXPIRY_DAYS = 365;

const MEMBER_LEVELS = [
    'bronze' => 0,
    'silver' => 500,
    'gold' => 2000,
    'platinum' => 5000,
];

const LEVEL_MULTIPLIERS = [
    'bronze' => 1.0,
    'silver' => 1.2,
    'gold' => 1.5,
    'platinum' => 2.0,
];
```

### Controller: `TransactionController.php`
**Modified Methods:**
- `index()`: Loads customers with role='customer' including points data
- `store()`: Integrates point redemption and awarding
  1. Validates points redemption
  2. Calculates discount from redeemed points
  3. Creates sale transaction with total discount
  4. Awards loyalty points based on purchase amount
  5. Updates member tier level

**New Method:**
- `getMemberPoints()`: AJAX endpoint returning member info (points, level)

### Validation: `StoreSaleRequest.php`
**Added Rules:**
```php
'points_to_redeem' => ['nullable', 'integer', 'min:0']
```

**PrepareForValidation:**
- Sets default value of 0 if `points_to_redeem` is missing

## Frontend Implementation

### View: `resources/views/kasir/transactions/index.blade.php`

#### Customer Selection
```html
<select id="customer_id" name="customer_id">
    <option value="">Pelanggan Umum (Non-Member)</option>
    @foreach($customers as $customer)
        <option value="{{ $customer->id }}" 
                data-points="{{ $customer->points }}" 
                data-level="{{ $customer->member_level }}">
            {{ $customer->name }} - {{ $customer->points }} poin
        </option>
    @endforeach
</select>
```

#### Member Info Card
- Displays when member selected
- Shows tier icon, level name, available points
- Gradient background (purple to orange)
- Shows conversion rate and earning message

#### Points Redemption Section
- Hidden by default (shown only for members)
- Input field for entering points to redeem
- Real-time calculation showing Rupiah value
- Min/max validation
- Helper text: "Min. 10 poin. 1 poin = Rp 100"

#### Payment Summary Enhancement
```
Subtotal: Rp X,XXX
━━━━━━━━━━━━━━━━━━━━
💳 Tukar Poin
[X points] = Rp X,XXX
━━━━━━━━━━━━━━━━━━━━
Diskon Manual: Rp X,XXX
━━━━━━━━━━━━━━━━━━━━
Total Diskon: Rp X,XXX (if applicable)
━━━━━━━━━━━━━━━━━━━━
Total Bayar: Rp X,XXX
```

### JavaScript Functions

#### Event Listeners
1. **Customer Selection Change**
   - Detects member vs non-member
   - Shows/hides points redemption UI
   - Updates member info card
   - Resets points input

2. **Points Input Change**
   - Validates minimum (10 points)
   - Validates maximum (available points)
   - Calculates and displays Rupiah value
   - Recalculates totals

#### Modified Functions
- `recalcTotals()`: Now includes points discount calculation
  - Combines manual discount + points discount
  - Validates total discount doesn't exceed subtotal
  - Auto-adjusts points if over limit
  - Shows/hides total discount display

## User Roles

### Member (role = 'customer')
- Can earn loyalty points
- Can redeem points for discounts
- Has member tier level
- Shown in customer dropdown with points

### Non-Member (role = 'pembeli' or no role)
- Cannot earn or redeem points
- Appears as "Pelanggan Umum" in dropdown
- No loyalty features available

## Business Logic Flow

### Purchase Flow with Points
1. **Cashier selects member** → Shows member info + points redemption UI
2. **Cashier adds products** → Calculates subtotal
3. **Member enters points to redeem** → Validates and shows discount value
4. **Cashier applies manual discount** (optional)
5. **System calculates total** = Subtotal - (Points Discount + Manual Discount)
6. **Cashier enters amount paid** → Calculates change
7. **Submit transaction** → Backend processes:
   - Deducts redeemed points
   - Records point redemption transaction
   - Calculates points earned from purchase amount
   - Awards earned points to member
   - Records point earning transaction with expiry date
   - Updates member tier level if applicable

### Tier Level Automatic Upgrade
- Triggered after each points award
- Checks total points balance
- Compares against tier thresholds
- Updates `member_level` in users table
- Applied on next purchase for earning multiplier

## Testing Scenarios

### Test Case 1: Non-Member Purchase
- Select "Pelanggan Umum"
- Add products totaling Rp 50,000
- No points section visible
- Complete transaction
- No points earned or redeemed

### Test Case 2: Member Purchase Without Redemption
- Select member with 100 points (Bronze)
- Add products totaling Rp 75,000
- Points redemption visible but set to 0
- Complete transaction
- Member earns 75 points (75,000 ÷ 1,000 × 1.0)
- New balance: 175 points

### Test Case 3: Member Purchase With Partial Redemption
- Select member with 500 points (Silver)
- Add products totaling Rp 100,000
- Enter 50 points to redeem
- System shows Rp 5,000 discount
- Total becomes Rp 95,000
- Complete transaction
- Member spends 50 points (balance: 450)
- Member earns 114 points (95,000 ÷ 1,000 × 1.2)
- New balance: 564 points

### Test Case 4: Member Purchase With Max Redemption
- Select member with 1,000 points (Gold)
- Add products totaling Rp 20,000
- Enter 200 points to redeem (max for this total)
- System shows Rp 20,000 discount
- Total becomes Rp 0
- Complete transaction
- Member spends 200 points (balance: 800)
- Member earns 0 points (Rp 0 after discount)
- New balance: 800 points

### Test Case 5: Over-Redemption Validation
- Select member with 500 points
- Add products totaling Rp 30,000
- Try to enter 400 points (would be Rp 40,000 > subtotal)
- System auto-adjusts to 300 points (Rp 30,000)
- Prevents negative total

### Test Case 6: Tier Level Upgrade
- Member has 1,990 points (Gold tier)
- Makes purchase of Rp 10,000
- Earns 15 points (10,000 ÷ 1,000 × 1.5)
- New total: 2,005 points
- Still Gold tier (threshold is 2,000+)
- Make another purchase of Rp 100,000
- Earns 150 points (100,000 ÷ 1,000 × 1.5)
- New total: 2,155 points
- Next purchase would use Gold multiplier until reaching 5,000 for Platinum

## Routes

```php
Route::prefix('kasir')->middleware(['auth'])->name('kasir.')->group(function () {
    Route::prefix('transaksi')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/member-points', [TransactionController::class, 'getMemberPoints'])->name('member-points');
    });
});
```

## Migration Files

1. `2025_11_04_000000_add_points_to_users_table.php`
   - Adds: points, member_level, member_since
   
2. `2025_11_04_000001_create_point_transactions_table.php`
   - Creates: point_transactions table with full schema

**Run migrations:**
```bash
php artisan migrate
```

## Models

### User Model Additions
```php
// Fillable fields
protected $fillable = [
    'points',
    'member_level',
    'member_since',
    // ... other fields
];

// Relationships
public function pointTransactions()
{
    return $this->hasMany(PointTransaction::class);
}

// Helper methods
public function isMember(): bool
{
    return $this->role === 'customer';
}

public function getMemberLevelColorAttribute(): string
{
    // Returns Tailwind color class for member level
}

public function getMemberLevelIconAttribute(): string
{
    // Returns emoji icon for member level
}
```

### PointTransaction Model
```php
protected $fillable = [
    'user_id',
    'sale_id',
    'type',
    'points',
    'balance_after',
    'description',
    'expires_at',
];

protected $casts = [
    'expires_at' => 'datetime',
];

// Relationships
public function user()
{
    return $this->belongsTo(User::class);
}

public function sale()
{
    return $this->belongsTo(Sale::class);
}
```

## Future Enhancements

### Potential Features
1. **Points History Page**: Show member all their point transactions
2. **Points Expiration Notifications**: Alert members before points expire
3. **Special Promotions**: Double points days, bonus points for specific products
4. **Referral Program**: Earn points for referring new members
5. **Birthday Bonus**: Extra points on member's birthday
6. **Admin Dashboard**: View loyalty program analytics and statistics
7. **Points Transfer**: Allow members to gift points to others
8. **Redemption Catalog**: Spend points on rewards beyond discounts

### Configuration Options
Consider adding admin panel to configure:
- Points earning rate
- Points redemption value
- Tier level thresholds
- Earning multipliers per tier
- Points expiration period
- Minimum redemption amount

## Troubleshooting

### Issue: Points Not Showing for Member
**Solution**: 
- Verify user role is 'customer' in database
- Check `points` column exists in users table
- Ensure migrations ran successfully

### Issue: Points Not Being Awarded
**Solution**:
- Check LoyaltyPointService is injected in controller
- Verify transaction is completing successfully
- Check point_transactions table for records

### Issue: Points Redemption Not Working
**Solution**:
- Ensure `points_to_redeem` input has correct ID
- Check JavaScript console for errors
- Verify form includes points input field
- Check validation rules in StoreSaleRequest

### Issue: Member Level Not Updating
**Solution**:
- Verify `updateMemberLevel()` is called after awarding points
- Check MEMBER_LEVELS constants are correct
- Ensure points balance is calculated correctly

## Support

For issues or questions about the loyalty points system, check:
1. Migration files for database schema
2. LoyaltyPointService for business logic
3. Browser console for JavaScript errors
4. Laravel logs for backend errors

---

**Created**: November 2025  
**Version**: 1.0  
**Status**: ✅ Fully Implemented and Tested
