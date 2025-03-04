import type { Customer } from "@/types/Customer";
import { personas } from "@dicebear/collection";
import { createAvatar } from "@dicebear/core";

export const useAvatar = () => {
  const generateAvatar = (customer: Customer) => {
    const avatar = createAvatar(personas, {
      seed: `${customer.first_name} ${customer.last_name}`,
    });

    return avatar.toDataUri();
  };

  return { generateAvatar };
};
