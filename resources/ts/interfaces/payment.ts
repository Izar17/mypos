export class Payment {
    label: string;
    identifier: string;
    selected: boolean;
    readonly: boolean;
    value: number;
    id?: number;
    total?: number; // LCABORNAY
    change?: number; // LCABORNAY
    reference?: string; // LCABORNAY
}