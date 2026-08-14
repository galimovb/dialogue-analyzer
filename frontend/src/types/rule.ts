import type { Severity } from "@/types/dialogue";

export interface Rule {
  id: number;
  code: string;
  name: string;
  severity: Severity;
  is_enabled: boolean;
  config: Record<string, unknown>;
  default_config: Record<string, unknown>;
  updated_at: string;
}

export interface UpdateRulePayload {
  is_enabled: boolean;
  severity: Severity;
  config: Record<string, unknown>;
}
