import { useIsMobile } from '@/hooks/useIsMobile';
import { DesktopShell } from '@/components/desktop/DesktopShell';
import { PhoneShell } from '@/components/phone/PhoneShell';

export function PortfolioPage() {
  const isMobile = useIsMobile(768);

  if (isMobile) {
    return <PhoneShell />;
  }

  return <DesktopShell isMobile={false} />;
}
