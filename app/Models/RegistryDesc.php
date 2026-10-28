<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class RegistryDesc extends Model {
    protected $table = 'registry_descs';
    protected $fillable = ['registry_id','managing_type','managing_office','third_party_company','project_roll_on','project_sign_off','estimation_project_duration'];
    protected $casts = ['managing_type'=>'integer','managing_office'=>'integer','project_roll_on'=>'date:Y-m-d','project_sign_off'=>'date:Y-m-d','estimation_project_duration'=>'integer'];
    public function registry(): BelongsTo { return $this->belongsTo(Registry::class,'registry_id'); }
    public function managingOfficeDepartment(): BelongsTo { return $this->belongsTo(Department::class, 'managing_office'); }
    public function managingTypeLibrary(): BelongsTo { return $this->belongsTo(ManagingType::class,'managing_type'); }
}
