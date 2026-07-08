export default function ViperaDevSignature({ asset = 'https://viperadev.com/ViperaDev-Logo.png', lightAsset = 'https://viperadev.com/ViperaDev-aydinlik.png' }) {
  return (
    <div className="vipera-signature">
      <span>Powered by</span>
      <img src={lightAsset} alt="ViperaDev" className="dark:hidden" />
      <img src={asset} alt="ViperaDev" className="hidden dark:inline-block" />
    </div>
  );
}
