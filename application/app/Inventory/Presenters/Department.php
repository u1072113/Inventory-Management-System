<?php namespace Inventory\Presenters;

class Department extends Presenter
{
    protected $entity;

    /**
     * @return string
     */
    public function budgetLimit()
    {
        return number_format($this->entity->budgetLimit, 2, ".", ",");
    }

    /**
     * @return string
     */
    public function dispatchSum()
    {
        if ($this->entity->dispatchsum != 0) {
            return number_format($this->entity->dispatchsum, 2, ".", ",");
        } else {
            return "-";
        }

    }

    /**
     * @return mixed
     */
    public function budgetStartDate()
    {
        return $this->entity->budgetStartDate->format('d/m/Y');
    }

    /**
     * @return mixed
     */
    public function budgetEndDate()
    {
        return $this->entity->budgetEndDate->format('d/m/Y');
    }

    /**
     * @return string
     */
    public function percentage()
    {
        if ($this->entity->dispatchsum > 0) {
            $val = ($this->entity->dispatchsum / $this->entity->budgetLimit) * 100;
        } else {
            return "100%";
        }
        if ($val > 100) {
            return "100%";
        } else {
            return $val . "%";
        }
    }
}
