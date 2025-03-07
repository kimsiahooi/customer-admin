export interface Customer {
  readonly id: string;
  image: string | null;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  bank_account_number: string;
  about: string | null;
  created_at: Date;
  updated_at: Date;
}
