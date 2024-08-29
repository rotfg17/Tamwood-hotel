import { atom, useRecoilState } from "recoil";

const userState = atom({
  key: "User",
  default: null,
});

const sidState = atom({
  key: "Sid",
  default: null,
});

export const useSession = () => {
  const [user, setUser] = useRecoilState(userState);
  const [sid, setSid] = useRecoilState(sidState);

  return {
    user,
    setUser,
    sid,
    setSid,
  };
};
